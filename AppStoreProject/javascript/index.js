// при зареждане на документа
$(document).ready(function(){
	//добавяне на onclick event на project div-ове
	$(".project").click(function() {
		var id = $(this)[0].id;
		window.location = "project.php?id=" + id;
	});
});
//добавяне на onclick event за бутона в upload.php за връщане към index.php
function Home() {
  window.location.href = "index.php";
}

function UploadForm() {
  window.location.href = "upload_form.php";
}

function toggleFormDisplay(){
  var form = document.getElementById("updateForm");
  if(form.style.display == "block") {
	form.style.display = "none";
	return;
  }
  form.style.display = "block";
}
