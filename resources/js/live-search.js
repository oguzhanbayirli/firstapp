import DOMPurify from "dompurify"

export default class Search {
  constructor() {
    this.injectHTML()
    this.cacheDOM()
    this.typingWaitTimer = null
    this.previousValue = ""
    this.debounceDelay = 750
    this.events()
  }

  cacheDOM() {
    this.headerSearchIcon = document.querySelector(".header-search-icon")
    this.overlay = document.querySelector(".search-overlay")
    this.closeIcon = document.querySelector(".close-live-search")
    this.inputField = document.querySelector("#live-search-field")
    this.resultsArea = document.querySelector(".live-search-results")
    this.loaderIcon = document.querySelector(".circle-loader")
  }

  events() {
    this.inputField.addEventListener("keyup", () => this.keyPressHandler())
    this.closeIcon.addEventListener("click", () => this.closeOverlay())
    this.headerSearchIcon.addEventListener("click", (e) => {
      e.preventDefault()
      this.openOverlay()
    })
    
    document.addEventListener("keydown", (e) => this.handleGlobalKeyPress(e))
  }

  handleGlobalKeyPress(e) {
    const isSearchKey = e.key.toUpperCase() === "S"
    const isOverlayHidden = !this.overlay.classList.contains("search-overlay--visible")
    const isNotTyping = !["INPUT", "TEXTAREA"].includes(document.activeElement.nodeName)

    if (isSearchKey && isOverlayHidden && isNotTyping) {
      this.openOverlay()
    }

    if (e.key === "Escape" && !isOverlayHidden) {
      this.closeOverlay()
    }
  }

  keyPressHandler() {
    const value = this.inputField.value.trim()

    if (!value) {
      this.resetSearch()
      return
    }

    if (value !== this.previousValue) {
      clearTimeout(this.typingWaitTimer)
      this.showLoaderIcon()
      this.hideResultsArea()
      this.typingWaitTimer = setTimeout(() => this.sendRequest(), this.debounceDelay)
    }

    this.previousValue = value
  }

  resetSearch() {
    clearTimeout(this.typingWaitTimer)
    this.hideLoaderIcon()
    this.hideResultsArea()
    this.previousValue = ""
  }

  async sendRequest() {
    try {
      const response = await axios.get(`/search/${encodeURIComponent(this.inputField.value)}`)
      this.renderResultsHTML(response.data)
    } catch (error) {
      console.error("Search error:", error)
      this.renderErrorMessage()
    }
  }

  renderResultsHTML(posts) {
    if (posts.length) {
      this.renderSuccessResults(posts)
    } else {
      this.renderNoResults()
    }
    this.hideLoaderIcon()
    this.showResultsArea()
  }

  renderSuccessResults(posts) {
    const resultCount = posts.length === 1 ? "1 item found" : `${posts.length} items found`
    const postsHTML = posts.map(post => this.createPostHTML(post)).join("")
    
    this.resultsArea.innerHTML = DOMPurify.sanitize(`
      <div class="list-group shadow-sm">
        <div class="list-group-item active">
          <strong>Search Results</strong> (${resultCount})
        </div>
        ${postsHTML}
      </div>
    `)
  }

  createPostHTML(post) {
    const postDate = new Date(post.created_at)
    const formattedDate = `${postDate.getMonth() + 1}/${postDate.getDate()}/${postDate.getFullYear()}`
    
    return `
      <a href="/post/${post.id}" class="list-group-item list-group-item-action">
        <img class="avatar-tiny" src="${post.user.avatar}" alt="${post.user.username}">
        <strong>${post.title}</strong>
        <span class="text-muted small">by ${post.user.username} on ${formattedDate}</span>
      </a>
    `
  }

  renderNoResults() {
    this.resultsArea.innerHTML = DOMPurify.sanitize(`
      <div class="alert alert-danger text-center shadow-sm">
        <i class="fas fa-search mr-2"></i>
        No results found for your search.
      </div>
    `)
  }

  renderErrorMessage() {
    this.resultsArea.innerHTML = DOMPurify.sanitize(`
      <div class="alert alert-warning text-center shadow-sm">
        <i class="fas fa-exclamation-triangle mr-2"></i>
        An error occurred. Please try again.
      </div>
    `)
    this.hideLoaderIcon()
    this.showResultsArea()
  }

  showLoaderIcon() {
    this.loaderIcon?.classList.add("circle-loader--visible")
  }

  hideLoaderIcon() {
    this.loaderIcon?.classList.remove("circle-loader--visible")
  }

  showResultsArea() {
    this.resultsArea?.classList.add("live-search-results--visible")
  }

  hideResultsArea() {
    this.resultsArea?.classList.remove("live-search-results--visible")
  }

  openOverlay() {
    this.overlay.classList.add("search-overlay--visible")
    document.body.style.overflow = "hidden"
    setTimeout(() => this.inputField?.focus(), 50)
  }

  closeOverlay() {
    this.overlay.classList.remove("search-overlay--visible")
    document.body.style.overflow = ""
    this.inputField.value = ""
    this.resetSearch()
  }

  injectHTML() {
    const searchHTML = `
      <div class="search-overlay">
        <div class="search-overlay-top shadow-sm">
          <div class="container container--narrow">
            <label for="live-search-field" class="search-overlay-icon">
              <i class="fas fa-search"></i>
            </label>
            <input 
              autocomplete="off" 
              type="text" 
              id="live-search-field" 
              class="live-search-field" 
              placeholder="What are you interested in?"
              aria-label="Search posts"
            >
            <span class="close-live-search" role="button" aria-label="Close search">
              <i class="fas fa-times-circle"></i>
            </span>
          </div>
        </div>
        <div class="search-overlay-bottom">
          <div class="container container--narrow py-3">
            <div class="circle-loader" role="status" aria-label="Loading"></div>
            <div class="live-search-results" role="region" aria-live="polite"></div>
          </div>
        </div>
      </div>
    `
    
    document.body.insertAdjacentHTML("beforeend", searchHTML)
  }
}
