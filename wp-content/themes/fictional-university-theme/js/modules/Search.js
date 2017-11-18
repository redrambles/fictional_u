import $ from 'jquery';

class Search {
  // 1. Describe and initiate our object
  constructor(){
    // must put the 'addSearchHTML' at the top because the properties below are looking for things that exist within the structure, which needs to exist first - hence calling the addSearchHTML structure function first
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
    // leverage our new custom rest route
    $.getJSON(universityData.root_url + '/wp-json/university/v1/search?term=' + this.searchField.val(), (results) => { this.searchResults.html(`
          <div class="row">

          <div class="one-third">
            <h2 class="search-overlay__section-title">General Information</h2>
            ${results.generalInfo.length ? '<ul class="link-list min-list">' : '<p>No results matches that search.</p>'}
            ${results.generalInfo.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>
            ${item.type == 'post' ? ` by ${item.authorName} ` : ''} </li>`).join('')}
            ${results.generalInfo.length ? '</ul>' : ''}
          </div>

          <div class="one-third">
            <h2 class="search-overlay__section-title">Programs</h2>
            ${results.programs.length ? '<ul class="link-list min-list">' : `<p>No programs match that search. <a href="${universityData.root_url}/programs">View All Programs.</a></p>`}
            ${results.programs.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join('')}
            ${results.programs.length ? '</ul>' : ''}
        
            <h2 class="search-overlay__section-title">Professors</h2>
            ${results.professors.length ? '<ul class="professor-cards">' : '<p>No professors match that search.</p>'}
            ${results.professors.map(item => `
            <li class="professor-card__list-item">
              <a class="professor-card" href="${item.permalink}">
                <img class="professor-card__image" src="${item.image}">
                <span class="professor-card__name">${item.title}</span>
              </a>
            </li>
            `).join('')}
            ${results.professors.length ? '</ul>' : ''}
          </div>

          <div class="one-third">
            <h2 class="search-overlay__section-title">Campuses</h2>
            ${results.campuses.length ? '<ul class="link-list min-list">' : `<p>No campuses match that search. <a href="${universityData.root_url}/campuses">View All Campuses.</a></p>`}
            ${results.campuses.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join('')}
            ${results.campuses.length ? '</ul>' : ''}

            <h2 class="search-overlay__section-title">Events</h2>
            ${results.events.length ? '' : `<p>No events matches that search.</p> <a href="${universityData.root_url}/events">View All Events.</a></p>`}
            ${results.events.map(item => `
            <div class="event-summary">
              <a class="event-summary__date t-center" href="${item.permalink}">
                <span class="event-summary__month">${item.month}</span>
                <span class="event-summary__day">${item.day}</span>  
              </a>
              <div class="event-summary__content">
                <h5 class="event-summary__title headline headline--tiny"><a href="${item.permalink}">${item.title}</a></h5>
                <p>${item.description}<a href="<${item.permalink}>" class="nu gray">Learn more</a></p>
              </div>
            </div>
            `).join('')}
          </div>
        </div><!-- .row -->
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
    // this will prevent the default link from taking us to the /search page for those of us with JS enabled
    return false;    
  }

  closeOverlay(){
    $("body").removeClass("body-no-scroll");
    this.searchOverlay.removeClass("search-overlay--active");  
    this.isOverlayOpen = false;  
  }

  keyPressDispatcher(e){
    if (e.keyCode == '83' && !this.isOverlayOpen && !$("input, textarea").is(':focus')) {
      this.openOverlay();
    }
    if (e.keyCode == '27' && this.isOverlayOpen) {
      this.closeOverlay();      
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