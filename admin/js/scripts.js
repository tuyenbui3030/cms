// $(document).ready(function(){
//     // EDITOR CKEDITOR
//     ClassicEditor
//         .create( document.querySelector( '#body' ) )
//         .catch( error => {
//             console.error( error );
//         } );
//         // REST OF THE CODE
//     });



// tinymce.init({ selector: 'textarea' });

ClassicEditor
.create( document.querySelector( '#body' ) )
.catch( error => {
    console.error( error );
} );
$(document).ready(function () {
    $('#selectAllBoxes').click(function (event) {
        if (this.checked) {
            $('.checkBoxes').each(function () {
                this.checked = true;
            });
        }
        else {
            $('.checkBoxes').each(function () {
                this.checked = false;
            });
        }
    });
});