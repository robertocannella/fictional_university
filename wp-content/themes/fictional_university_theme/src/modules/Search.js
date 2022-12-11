import axios from "axios";


class Search {
    typingTimer;
    // 1. describe and create/initiate our object
    constructor() {
        this.appendSearchHTML()

        this.allInputs = document.querySelectorAll('input');
        this.allSelects = document.querySelectorAll('select');
        this.allTextAreas = document.querySelectorAll('textarea');
        this.openButtons = document.querySelectorAll('.js-search-trigger');
        this.closeButtons = document.querySelectorAll('.search-overlay__close');
        this.searchOverlay = document.getElementById('search-overlay');
        this.searchField = document.getElementById("search-term");
        this.resultsDiv = document.getElementById("search-overlay__results");

        this.events() // add all event listeners
        this.isOverlayOpen = false
        this.isSpinnerVisible = false
        this.previousValue = ''
        //this.typingTimer
        this.getResults = this.getResults.bind(this)
    }

    // 2. events
    events() {

        this.openButtons.forEach(el=>{
            el.addEventListener('click',this.openOverlay.bind(this))
        })
        this.closeButtons.forEach(el=>{
            el.addEventListener('click',this.closeOverlay.bind(this))
        })
        document.addEventListener("keydown", this.keyPressDispatcher.bind(this))
        this.searchField.addEventListener("keyup", this.typingLogic.bind(this))

    }

    // 3. methods (function, action...)
    typingLogic() {
        if (this.searchField.value !== this.previousValue) {
            clearTimeout(this.typingTimer)

            if (this.searchField.value) {
                if (!this.isSpinnerVisible) {
                    this.resultsDiv.innerHTML = '<div class="spinner-loader"></div>'
                    this.isSpinnerVisible = true
                }
                this.typingTimer = setTimeout(this.getResults.bind(this), 500)
            } else {
                this.resultsDiv.innerHTML = ''
                this.isSpinnerVisible = false
            }
        }

        this.previousValue = this.searchField.value
    }
    async getResults() {
        // The globalSiteData Object is created inside a PHP function: wp_localize_script()
        const asyncResults = Promise.all([
            axios.get(`${globalSiteData.siteUrl}/wp-json/wp/v2/posts`,{params:{search: this.searchField.value}}),
            axios.get(`${globalSiteData.siteUrl}/wp-json/wp/v2/pages`, {params:{search: this.searchField.value}})
        ])
        try {
            const res = await asyncResults;

            const data = res.map((res) => res.data);
            const combinedResults = data.flat()

            if (!combinedResults.length){
                this.resultsDiv.innerHTML = `
            <div>
                <h1>No Results</h1>
                <p>Try again.</p>
            </div>
        `
            }else{
                let results = '';
                combinedResults.map((item)=>(
                    // results += "<li> <a href=\"" + item.link + "\">" + item.title.rendered +  "</li>"
                    results += `<li> <a href="${item.link}">${item.title.rendered}</a> ${item.type == 'post' ? `by: ${item.authorName}` : ""}</li>`
                ))

                this.resultsDiv.innerHTML = `
            <div>
                <h2 class="search-overlay__section-title">Results</h2>
                <ul class="link-list min-list">
                    ${results}
                </ul>
            </div>
        `
            }

        }catch (err) {
           this.resultsDiv.innerHTML = `
           <h2>Unexpected Error, Please Try again.</h2>
           <p>${err.message}</p>`;
        }
        this.isSpinnerVisible = false
    }

    keyPressDispatcher(e) {

        if (e.keyCode == 83 && !this.isOverlayOpen && !document.querySelector(":focus"))
           this.openOverlay()

        if (e.keyCode == 27 && this.isOverlayOpen)
            this.closeOverlay()

    }

    openOverlay() {
        this.searchOverlay.classList.add("search-overlay--active")
        document.getElementById("body").classList.add("body-no-scroll")
        this.searchField.value = ''
        setTimeout( ()=> this.searchField.focus() ,301)
        this.isOverlayOpen = true
    }

    closeOverlay() {
        this.searchOverlay.classList.remove("search-overlay--active")
        document.getElementById("body").classList.remove("body-no-scroll")
        this.isOverlayOpen = false
    }
    appendSearchHTML (){
        const searchDiv = document.createElement('div');
        searchDiv.innerHTML = `
             <div class="search-overlay" id="search-overlay">
                <div class="search-overlay__top">
                    <div class="container">
                        <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
                        <input autocomplete="off" type="text" class="search-term" placeholder="What are you looking for?" id="search-term">
                        <i class="fa fa-window-close search-overlay__close" id="search-overlay__close" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="container">
                    <div id="search-overlay__results"></div>
                </div>
            </div>
        `;
        document.body.appendChild(searchDiv);
    }
}

export default Search