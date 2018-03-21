import $ from 'jquery';

class MyNotes {
  constructor() {
   this.events();
  }

  // Event Handlers
  events() {
    $("#my-notes").on("click", ".delete-note", this.deleteNote );
    $("#my-notes").on("click", ".edit-note", this.editNote.bind(this));
    $("#my-notes").on("click", ".update-note", this.updateNote.bind(this));
    $(".submit-note").on("click", this.createNote.bind(this));
  }


  // Methods 
  editNote(e){
    var thisNote = $(e.target).parents("li");
    if (thisNote.data("state") == "editable"){ // "state" is not yet set with a new note so will always trigger the 'makeNoteEditable' method
      this.makeNoteReadOnly(thisNote);
    } else {
      this.makeNoteEditable(thisNote);
    }
  }

  makeNoteEditable(thisNote){
    thisNote.find(".edit-note").html('<i class="fa fa-times" aria-hidden="true"></i>Cancel'); // Change button test from 'Edit' to 'Cancel'
    thisNote.find(".note-title-field, .note-body-field").removeAttr("readonly").addClass("note-active-field"); // Allow editing of title and content
    thisNote.find(".update-note").addClass("update-note--visible"); // Make 'Save' button visible
    thisNote.data("state", "editable"); // Change state
  }

  makeNoteReadOnly(thisNote){
    thisNote.find(".edit-note").html('<i class="fa fa-pencil" aria-hidden="true"></i>Edit');
    thisNote.find(".note-title-field, .note-body-field").attr("readonly", "readonly").removeClass("note-active-field");
    thisNote.find(".update-note").removeClass("update-note--visible");
    thisNote.data("state", "cancel");
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
        if (response.userNoteCount < 5) {
          $(".note-limit-message").removeClass("active");
        }
      },
      error: (error) => {
        console.log("Sorry - did not work");
        console.log(error);
      }
    });
  }

  updateNote(e) {
    var thisNote = $(e.target).parents("li");
    var ourUpdatedPost = {
      'title': thisNote.find(".note-title-field").val(), // grab edited title
      'content': thisNote.find(".note-body-field").val() // grab edited content
    }

    $.ajax({
      // This will prove to WP that we are logged in and have permission to delete note
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
      },
      url: universityData.root_url + '/wp-json/wp/v2/note/' + thisNote.data('id'),
      type: 'POST',
      data: ourUpdatedPost,
      success: (response) => {
        this.makeNoteReadOnly(thisNote);
        console.log("Congrats, changed post!");
        console.log(response);
      },
      error: (error) => {
        console.log("Sorry - did not work");
        console.log(error);
      }
    });
  }

  createNote() {

    var ourNewPost = {
      'title': $(".new-note-title").val(),
      'content': $(".new-note-body").val(),
      'status': 'publish' // will enforce private status on server side
    }

    $.ajax({
      // This will prove to WP that we are logged in and have permission to delete note
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
      },
      url: universityData.root_url + '/wp-json/wp/v2/note/',
      type: 'POST',
      data: ourNewPost,
      success: (response) => {
        $(".new-note-title, .new-note-body").val(''); // Empty out the fields
        $(`
          <li data-id="${response.id}">
            <input readonly class="note-title-field" value="${response.title.raw}">
            <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i>Edit</span>
            <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i>Delete</span>
            <textarea readonly class="note-body-field">${response.content.raw}</textarea>
            <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i>Save</span>
          </li>  
          `).prependTo("#my-notes").hide().slideDown(); // Slide the new note into view at the top of the list
        console.log("Congrats, changed post!");
        console.log(response);
      },
      error: (error) => {
        if (error.responseText == "You have reached your note limit.") {
          $(".note-limit-message").addClass("active");
        }
        console.log("Sorry - did not work");
        console.log(error);
      }
    });
  }

}

export default MyNotes;