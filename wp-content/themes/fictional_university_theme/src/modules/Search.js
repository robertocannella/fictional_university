import axios from "axios";


class Search {
    typingTimer;
    // 1. describe and create/initiate our object
    constructor() {


        this.openButtons = document.querySelectorAll('.js-search-trigger')
        this.closeButtons = document.querySelectorAll('.search-overlay__close')
        this.searchOverlay = document.getElementById('search-overlay')
        this.searchField = document.getElementById("search-term")
        this.resultsDiv = document.getElementById("search-overlay__results")

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

        let url  = 'https://www.robertocannella.com/fictional_university/wp-json/wp/v2/posts';

        let res = await axios.get( url,{
            params:{
                search: this.searchField.value
            }
        })

        if (!res.data[0]){
            this.resultsDiv.innerHTML = `
            <div>
                <h1>No Results</h1>
                <p>Try again.</p>
            </div>
        `
        }else{
            let results = '';
            res.data.map((item)=>(
               // results += "<li> <a href=\"" + item.link + "\">" + item.title.rendered +  "</li>"
                results += `<li> <a href="${item.link}">${item.title.rendered}</li>`
            ))
            console.log(results)
            this.resultsDiv.innerHTML = `
            <div>
                <h1>Results</h1>
                <ul>
                    ${results}
                </ul>
            </div>
        `
        }

            // .then( (response) =>{
            //     this.resultsDiv.innerHTML = `
            //         <div>
            //             <h1>Results</h1>
            //             <a href="#"> </a>
            //                 ${response.data[0]}
            //         </div>
            //
            //         `;
            //     console.log(response.data[0]);
            // })
            // .catch( (error)=> {
            //     console.log(error);
            // })
            // .then( ()=> {
        this.isSpinnerVisible = false
            // });


    }

    keyPressDispatcher(e) {
        if (!document.activeElement)
            this.openOverlay()
        // if (e.keyCode == 83 && !this.isOverlayOpen && !document.getElementsByClassName("input, textarea").is(":focus")) {
        //     this.openOverlay()
        // }

        if (e.keyCode == 27 && this.isOverlayOpen) {
            this.closeOverlay()
        }
    }

    openOverlay() {
        this.searchOverlay.classList.add("search-overlay--active")
        document.getElementById("body").classList.add("body-no-scroll")
        this.isOverlayOpen = true
    }

    closeOverlay() {
        this.searchOverlay.classList.remove("search-overlay--active")
        document.getElementById("body").classList.remove("body-no-scroll")
        this.isOverlayOpen = false
    }
}

export default Search