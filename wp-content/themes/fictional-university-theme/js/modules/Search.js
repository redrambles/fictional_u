import $ from 'jquery';

class Search {
  // 1. Describe and initiate our object
  constructor(){
    // must put this at the top because the following properties are looking for things that exist within the structure, which needs to exist first - hence calling the addSearchHTML structure function first
    this.addSearchHTML();
    this.openButton = $(".js-search-trigger");
    this.closeButton = $(".search-overlay__close");
    this.searchOverlay = $(".search-overlay");
    this.searchField = $("#search-term");
    this.isOverlayOpen = false;
    this.typingTimer;
    this.searchResults = $("#search-overlay__results");
    this.isSpinnerSpinning = false;
    this.previousSearchValue;
    this.events();
  }

  // 2. Events
  events() {
    this.openButton.on("click", this.openOverlay.bind(this));
    this.closeButton.on("click", this.closeOverlay.bind(this));
    $(document).on("keydown", this.keyPressDispatcher.bind(this));
    this.searchField.on("keyup", this.typingLogic.bind(this));
  }

  // 3. Methods

  typingLogic(){
    // This will make it so if you just move your cursor or press some key that has no bearing on the word(s) in the search field, that you won't trigger the spinner and/or search
    if( this.searchField.val() != this.previousSearchValue ){
    // This will clear the function if another key is pressed before the delay is complete
      clearTimeout(this.typingTimer);
      if ( this.searchField.val() != "" ) {
        if ( !this.isSpinnerSpinning ){
          this.searchResults.html('<div class="spinner-loader"></div>');
          this.isSpinnerSpinning = true;      
        }
        this.typingTimer = setTimeout( this.getResults.bind(this), 600);
      } else {
        this.searchResults.html('');
        this.isSpinnerSpinning = false;
      }
    }
    this.previousSearchValue = this.searchField.val();
  }

  getResults(){
    $.getJSON(universityData.root_url + '/wp-json/wp/v2/posts?search=' + this.searchField.val(), data => {
      // We can use ternary operators to check conditions in template literals - but not if statements
        this.searchResults.html(`
          <h2 class="search-overlay__section-title">General information</h2>
          ${data.length ? '<ul class="link-list min-list">' : '<p>No results matches that search.</p>'}
            ${data.map(item => `<li><a href="${item.link}">${item.title.rendered}</a></li>`).join('')}
          ${data.length ? '</ul>' : ''}
        `);
      this.isSpinnerSpinning = false;
    });
  }

  openOverlay(){
    $("body").addClass("body-no-scroll");
    this.searchOverlay.addClass("search-overlay--active");
    this.searchField.val('');
    setTimeout(() => this.searchField.focus(), 301); // to give the overlay time to fully fade in
    this.isOverlayOpen = true;    
  }

  closeOverlay(){
    $("body").removeClass("body-no-scroll");
    this.searchOverlay.removeClass("search-overlay--active");  
    this.isOverlayOpen = false;  
  }

  keyPressDispatcher(e){
    if (e.keyCode == '83' && !this.isOverlayOpen && !$("input, textarea").is(':focus')) {
      this.openOverlay();
      console.log('open overlay');
    }
    if (e.keyCode == '27' && this.isOverlayOpen) {
      this.closeOverlay();
      console.log('close overlay');
      
    }
  }

  // Search Overlay Structure
  addSearchHTML() {
    $("body").append(`

    <div class="search-overlay">
      <div class="search-overlay__top">
        <div class="container">
          <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
          <input id="search-term" text="text" class="search-term" placeholder="What are you looking for?">
          <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
        </div>
      </div>
    
      <div class="container">
        <div id="search-overlay__results">
        </div>
      </div>
    </div>
    `);
  }

}

export default Search;