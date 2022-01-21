const is_text = /^((\w+)[ .,]*)+$/;
const is_spaces = /^\s+$/;

function create_error_element(msg){
  console.log(msg);
  var newDiv = document.createElement("h3");
  newDiv.setAttribute("class", "error");
  newDiv.innerHTML = msg;
  return newDiv;
}
function clean_err_tags(){
    const elements = document.getElementsByClassName("error");
    while (elements.length > 0) elements[0].remove();
}

function validate(){
  clean_err_tags();
  var pass  = true;
  var title = document.getElementById("projectTitle").value;
  var descr = document.getElementById("projectDescription").value;
  
  if (title.length < 3 || title.length > 30) {
  document.getElementById("error_msg").appendChild(create_error_element("Title should be at between 3 and 30 characters"));
  console.log(1);
    pass = false;
  }
  if (!is_text.test(title)){
      document.getElementById("error_msg").appendChild(create_error_element("Title has unsupported characters"));
  console.log(2);
    pass = false;
  }
  if ( is_spaces.test(title)){
  document.getElementById("error_msg").appendChild(create_error_element("Ttile is of empty characters"));
  console.log(3);
    pass = false;
  }

  if (descr.length < 20 || descr.length > 300) {
  document.getElementById("error_msg").appendChild(create_error_element("Description should be between 20 and 300 characters"));
  console.log(4);
    pass = false;
  }
  if (!is_text.test(descr)){
  document.getElementById("error_msg").appendChild(create_error_element("Description has unsupported characters "));
  console.log(5);
    pass = false;
  }
  if ( is_spaces.test(descr)){
  document.getElementById("error_msg").appendChild(create_error_element("Description is only empty characters "));
  console.log(6);
    pass = false;
  }
  console.log(pass);
  return pass;
}


