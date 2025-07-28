// client side validation module
// Disable form submissions if there are invalid fields
(function() {
    'use strict';
    window.addEventListener('load', function() {
      // Get the forms we want to add validation styles to
      var forms = document.getElementsByClassName('needs-validation');
      // Loop over them and prevent submission
      var validation = Array.prototype.filter.call(forms, function(form) {
        form.addEventListener('submit', function(event) {
          if (form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
          }
          form.classList.add('was-validated');
        }, false);
      });
    }, false);
  })();

//function to reset a form, this will reset with all those validation errors
function resetForm(formElement){
    formElement.classList.remove('was-validated');
    formElement.reset();
}

/** custom validation ****/
function isNumber(event) {
    //event.preventDefault();
    var key = window.event ? event.keyCode : event.which;
    if (event.keyCode === 8 || event.keyCode === 46) {
        return true;
    } else if ( key < 48 || key > 57 ) {
        return false;
    } else {
        return true;
    }
}

function isChar(event) {
    var key = window.event ? event.keyCode : event.which;
    if (event.keyCode === 8 || event.keyCode === 46) {
        return true;
    }
    else if((key>=65 && key<=91) || (key>=97 && key<=122)){
        return true;
    }
    else {
        return false;
    }
}

function isValidEmail(email) 
{
    var re = /^(?:[a-z0-9!#$%&amp;'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&amp;'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])$/;
    return re.test(email);
}
//function to convert form data to json
function getFormDataAsJson(form){
    var obj = {};//json object
    var curr_element; //current element
    var curr_key = "";//current key
    var curr_val = "";//current value
    var array_val = {};//Object of array of values of same key name
    var radio_val = {};//Object of value of the last checked radio button
    
    for(var i=0;i<form.elements.length;i++){
        //check for every element in the form 
        curr_element = form.elements[i];
        if(curr_element.name.trim() == ""){
            continue;
        }
        if((curr_element.type).toLowerCase()!=="submit" && (curr_element.type).toLowerCase()!=="button" && (curr_element.type).toLowerCase()!=="reset"){
            curr_key = curr_element.name.trim();
            
            curr_key = curr_key.replace("[]","");
            curr_val = curr_element.value.trim();
            
            if(array_val[curr_key] === undefined){
                array_val[curr_key] = [];
            }
            if(radio_val[curr_key] === undefined){
                radio_val[curr_key] = "";
            }
            var key_name = curr_element.name.trim();
            //check if key name is of array type, which means the key name can have multiple values as an array
            if(key_name.substring(key_name.length-2, key_name.length)=="[]"){
                //For checkbox with same key name
                if((curr_element.type).toLowerCase()==="checkbox"){
                    if(curr_element.checked === true){
                        array_val[curr_key].push(curr_val);
                    }
                    obj[curr_key]=array_val[curr_key];
                }
                else{
                    if(curr_val!==""){
                        array_val[curr_key].push(curr_val);
                    }
                    obj[curr_key]=array_val[curr_key];
                }
                
            }
            else{
                if((curr_element.type).toLowerCase()==="checkbox"){
                    obj[curr_key] = (curr_element.checked === true)?curr_val:"";
                }
                else if((curr_element.type).toLowerCase() === "radio"){
                    if(curr_element.checked === true){
                        //replace last value of checked radio button with the 
                        //new value of radio button if checked
                        radio_val[curr_key] = curr_val;
                    }
                    obj[curr_key]= radio_val[curr_key];
                }
                else{
                    obj[curr_key] = curr_val;
                }
            }
        }
    }
    return obj;
}