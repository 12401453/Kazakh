
function tt_type() {

  var spoofspan = document.getElementById('spoofspan');
  var spanDisplay = spoofspan.style.display;

if (spanDisplay == 'inline') {

spoofspan.style.display = 'none';
document.getElementById("tt_styles").setAttribute("href", "tooltip_none_style_2.css");

}
else {
spoofspan.style.display = 'inline';
document.getElementById("tt_styles").setAttribute("href", "tooltip_eng_style_2.css");
}
}


function selectText() {

  let textselect_value = document.getElementById('textselect').value;
  let post_data = "textselect="+textselect_value;
  console.log(post_data);
  if (textselect_value == 0) { return; }

  const httpRequest = (method, url) => {

     
      let para1 = document.getElementById('p1');

      const xhttp = new XMLHttpRequest();
      xhttp.open(method, url, true);
      xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

      xhttp.onload = () => {
        if(xhttp.readyState == 4) {
          para1.innerHTML = xhttp.responseText;
        }
        else {
            para1.innerHTML = "Something went wrong";
        }
      }

      xhttp.send(post_data);

}

  httpRequest("POST", "retrieve_text.php");

}




function loadText() {
  
  document.body.style.cursor='progress';
  document.getElementById('add_btn').style.cursor = 'progress';
 const httpRequest = (method, url) => {

   var send_data = "new_text="+document.getElementById('newtext').value+"&text_title="+document.getElementById('text_title').value;

   const xhttp = new XMLHttpRequest();
   xhttp.open(method, url, true);
  // xhttp.responseType = 'json';
  xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
   
   xhttp.onload = () => {
     console.log("sent");
    // console.log(xhttp.responseText);
    if(xhttp.readyState == 4) location.reload();
   }
   xhttp.send(send_data);

 

 }

 httpRequest("POST", "update_db.php");
 
}



function clearTable() {
  

  const httpRequest = (method, url) => {
 
    const xhttp = new XMLHttpRequest();
    xhttp.open(method, url, true);
   // xhttp.responseType = 'json';
   // xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    
    xhttp.onload = () => {
      console.log("sent");
     // console.log(xhttp.responseText);
     if(xhttp.readyState == 4) location.reload();
    }
    xhttp.send();
  }
 
  httpRequest("POST", "clear_table.php");
 }





  window.addEventListener("keydown", event => {
    if (event.repeat) {return;}
    if (event.key == "c") {
      document.getElementById("tt_styles").href = "tooltip_cyr_style_2.css";
    }
  });

  window.addEventListener("keyup", event => {
    var spanDisplay = spoofspan.style.display;
    if (event.key == "c") {

      if(spanDisplay == 'inline') { document.getElementById("tt_styles").href = "tooltip_eng_style_2.css"; }
      else { document.getElementById("tt_styles").href = "tooltip_none_style_2.css";}
    }
  });

