function selectText_js_tt() {
  

    let loadingbutton = document.createElement('div');
    loadingbutton.innerHTML = "Loading...";
    loadingbutton.id = 'loadingbutton';
    document.getElementById('spoofspan').after(loadingbutton);
  
    let textselect_value = document.getElementById('textselect').value;
    let post_data = "textselect="+textselect_value;
    console.log(post_data);
    const httpRequest = (method, url) => {
  
       
        let para1 = document.getElementById('p1');
  
        const xhttp = new XMLHttpRequest();
        xhttp.open(method, url, true);
        xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  
        xhttp.onreadystatechange = () => {
        
           
      
          if(xhttp.readyState == 4) {
            para1.innerHTML = xhttp.responseText;
            loadingbutton.remove();
          }
       
        }
  
        xhttp.send(post_data);
  
  }
  
    httpRequest("POST", "retrieve_text_js_tt.php");
  
  }