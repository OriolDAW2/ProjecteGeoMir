// Load our customized validationjs library
import Validator from '../validator'
 
// Submit form ONLY when validation is OK
const form = document.getElementById("create_places");
var error_body = document.querySelector("#error_body");
var error_lat = document.querySelector("#error_lat");
var error_long = document.querySelector("#error_long");
var error_file = document.querySelector("#error_file");
 
form.addEventListener("submit", function( event ) {
   // Reset errors messages
   error_body.innerHTML = "";
   error_lat.innerHTML = "";
   error_long.innerHTML = "";
   error_file.innerHTML = "";

   // Create validation
   let data = {
       "body": document.getElementsByName("body")[0].value,
       "latitude": document.getElementsByName("latitude")[0].value,
       "longitude": document.getElementsByName("longitude")[0].value,
       "upload": document.getElementsByName("upload")[0].value,
   }
   let rules = {
       "body": "required",
       "latitude": "required",
       "longitude": "required",
       "upload": "required",
   }
   let validation = new Validator(data, rules)
   // Validate fields
   if (validation.passes()) {
       // Allow submit form (do nothing)
       console.log("Validation OK")
    } else {
       // Get error messages
        let errors = validation.errors.all()
        console.log(errors)
        // Show error messages

        let errorPlaces = [];
        for(let inputName in errors) {
            if(currentLocale == 'ca'){
                errorPlaces.push("El camp " + inputName + " es obligatori!");
                if (error_body == 0) {
                    error_body.innerHTML = errorPost[0];
                }else if (error_body == 0 && error_lat) {
                    
                }
                error_lat.innerHTML = errorPlaces[1];
                error_long.innerHTML = errorPlaces[2];
                error_file.innerHTML = errorPlaces[3];

            }else if(currentLocale == 'es'){
                error_places.innerHTML = "El campo " + inputName + " es obligatorio!";
                error_places.hidden = false ;
            }else if(currentLocale == 'en'){
                error_places.innerHTML = "Field " + inputName + " is required!";
                error_places.hidden = false ;
            }
        }
       // Avoid submit
       event.preventDefault()
       return false
   }
})
