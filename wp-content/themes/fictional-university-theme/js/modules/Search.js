import $ from 'jquery';

class Search {
  // 1. Describe and initiate our object
  constructor(){
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
        this.typingTimer = setTimeout( this.getResults.bind(this), 750);
      } else {
        this.searchResults.html('');
        this.isSpinnerSpinning = false;
      }
    }
    this.previousSearchValue = this.searchField.val();
  }

  getResults(){
    this.searchResults.html("search results!");
    this.isSpinnerSpinning = false;
  }

  openOverlay(){
    $("body").addClass("body-no-scroll");
    this.searchOverlay.addClass("search-overlay--active");
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

}

export default Search;