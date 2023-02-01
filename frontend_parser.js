//this works but the PHP is only about 16% faster even with pre-parsed data and I don't know why so I'm shelving it
const loadText_New = function () {
    let text = document.getElementById("newtext").value.trim();
    let text_title = encodeURIComponent(document.getElementById('text_title').value.trim());
    if(text_title == '' && text == '') { return; }
    if(text_title == '') { alert("Do not leave Text Title blank"); return; }
    if(text == '') {alert("You cannot submit a blank text"); return; }
  
    let langselect = document.getElementById('langselect').value;
    let space_index = new Array();
    let tokens = text.split(' ');
    let text_word = "";
    let word_eng_word = "";
    let is_a_word = true;
    let shit = 0;
    let unshit = 0;
    let text_words = new Array();
    let word_eng_words = new Array();
    for(let token of tokens) {
        shit = 0;
        unshit = 0;
        while(unshit != -1 && shit != -1) {
            word_eng_word = "";
            shit = token.search(/\P{L}/u);
            unshit = token.search(/\p{L}/u);
            if(shit == 0) {
                is_a_word = false;
                if(unshit == -1) {
                    text_word = token;  
                }
                else {
                    text_word = token.slice(0,unshit);
                    token = token.slice(unshit);
                }
            }
            else if(shit == -1) {
                is_a_word = true;
                text_word = token;    
            }
            else {
                is_a_word = true;
                text_word = token.slice(0, shit);
                token = token.slice(shit);
            }      
            if(is_a_word) {
                if(lang_id == 7) word_eng_word = text_word.toLocaleLowerCase('tr');
                else word_eng_word = text_word.toLowerCase();
                word_eng_word = encodeURIComponent(word_eng_word);
            }
    
            text_words.push(encodeURIComponent(text_word.replaceAll(/[\n\r]/g, '\\n').replaceAll('\u0022', '\u005C\u0022')));
            word_eng_words.push(encodeURIComponent(word_eng_word));
        }
        space_index.push(text_words.length - 1);
    }
  
    let post_data = "lang_id="+langselect+"&text_words="+JSON.stringify(text_words)+"&word_eng_words="+JSON.stringify(word_eng_words)+"&space_index="+space_index.toString()+"&text_title="+text_title;
  
    let loadingbutton = document.createElement('div');
    loadingbutton.innerHTML = 'Processing text: <span id="percent">0.00%</span>';
    loadingbutton.id = 'loadingbutton';
    document.getElementById('spoofspan').after(loadingbutton);
  
    const httpRequest = (method, url) => { 
      const xhttp = new XMLHttpRequest();
      xhttp.open(method, url, true);
      xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
   
      xhttp.onload = () => {
        console.log("sent");
       if(xhttp.readyState == 4)  {
         loadingbutton.remove(); //not really needed
         //location.reload();
       }
      }
      xhttp.send(post_data);
    }
   
    httpRequest("POST", "update_db_New.php");
  };

