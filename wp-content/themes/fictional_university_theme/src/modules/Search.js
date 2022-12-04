


class Search {
    typingTimer;
    // 1. describe and create/initiate our object
    constructor() {
        this.resultsDiv = document.getElementById("search-overlay__results")
        this.openButton = document.getElementById('js-search-trigger')
        this.closeButton = document.getElementById('search-overlay__close')
        this.searchOverlay = document.getElementById('search-overlay')
        this.searchField = document.getElementById("search-term")
        this.events()
        this.isOverlayOpen = false
        this.isSpinnerVisible = false
        this.previousValue = ''
        //this.typingTimer

    }

    // 2. events
    events() {
        this.openButton.addEventListener('click',this.openOverlay.bind(this))
        this.closeButton.addEventListener('click',this.closeOverlay.bind(this))
        document.addEventListener("keydown", this.keyPressDispatcher.bind(this))
        this.searchField.addEventListener("keyup", this.typingLogic.bind(this))
    }


    // 3. methods (function, action...)
    typingLogic() {
        if (this.searchField.value !== this.previousValue) {
            console.log(this.searchField.value)
            clearTimeout(this.typingTimer)

            if (this.searchField.value) {
                if (!this.isSpinnerVisible) {
                    this.resultsDiv.innerHTML = '<div class="spinner-loader"></div>'
                    this.isSpinnerVisible = true
                }
                this.typingTimer = setTimeout(this.getResults.bind(this), 2000)
            } else {
                this.resultsDiv.innerHTML = ''
                this.isSpinnerVisible = false
            }
        }

        this.previousValue = this.searchField.value
    }
    getResults() {
        this.resultsDiv.innerHTML = "Imagine real search results here...";
        this.isSpinnerVisible = false
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