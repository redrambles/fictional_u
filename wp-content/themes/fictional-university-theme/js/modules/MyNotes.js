import $ from 'jquery';

class MyNotes {
  constructor() {
   this.events();
  }

  events() {
    $(".delete-note").on("click", this.deleteNote );
  }


  // Methods will go here
  deleteNote() {
    // .ajax is a great method when you want to control the type of request
    $.ajax({
      // This will prove to WP that we are logged in and have permission to delete note
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
      },
      url: universityData.root_url + '/wp-json/wp/v2/note/75',
      type: 'DELETE',
      success: (response) => {
        console.log("Congrats, that note was deleted");
        console.log(response);
      },
      error: (error) => {
        console.log("Sorry - did not work");
        console.log(error);
      }
    });
  }
}

export default MyNotes;