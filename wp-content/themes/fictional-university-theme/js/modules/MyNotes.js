import $ from 'jquery';

class MyNotes {
  constructor() {
   this.events();
  }

  events() {
    $(".delete-note").on("click", this.deleteNote );
    $(".edit-note").on("click", this.editNote );

  }


  // Methods will go here
  editNote(e){
    var thisNote = $(e.target).parents("li");
    thisNote.find(".note-title-field, .note-body-field").removeAttr("readonly").addClass("note-active-field");
    thisNote.find(".update-note").addClass("update-note--visible");
  }

  deleteNote(e) {
    // Grab the list element that the delete button is a span child of
    var thisNote = $(e.target).parents("li");
    // .ajax is a great method when you want to control the type of request
    $.ajax({
      // This will prove to WP that we are logged in and have permission to delete note
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
      },
      url: universityData.root_url + '/wp-json/wp/v2/note/' + thisNote.data('id'),
      type: 'DELETE',
      success: (response) => {
        console.log("Congrats, that note was deleted");
        thisNote.slideUp(); // when you hit 'Delete', fade up and out with the slideUp jQuery command
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