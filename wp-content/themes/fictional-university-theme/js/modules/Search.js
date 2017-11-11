import $ from 'jquery';

class Search {
  // 1. Describe and initiate our object
  constructor(){
    this.openButton = $(".js-search-trigger");
    this.closeButton = $(".search-overlay__close");
    this.searchOverlay = $(".search-overlay");
    this.isOverlayOpen = false;

    this.events();
  }

  // 2. Events
  events() {
    this.openButton.on("click", this.openOverlay.bind(this));
    this.closeButton.on("click", this.closeOverlay.bind(this));
    $(document).on("keydown", this.keyPressDispatcher.bind(this));
  }

  // 3. Methods
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
    if (e.keyCode == '83' && !this.isOverlayOpen) {
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