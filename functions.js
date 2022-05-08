
function tt_type() {

  var spoofspan = document.getElementById('spoofspan');
  var spanDisplay = spoofspan.style.display;

if (spanDisplay == 'inline') {

spoofspan.style.display = 'none';
document.getElementById("tt_styles").setAttribute("href", "tooltip_none_style_2.css");

}
else {
spoofspan.style.display = 'inline';
document.getElementById("tt_styles").setAttribute("href", "tooltip_edit.css");
}
}

let lang_id = 0;

function setLangId() {
  let textselect_value = document.getElementById('textselect').value;
  let post_data = "textselect=" + textselect_value;
  const httpRequest = (method, url) => {

    const xhttp = new XMLHttpRequest();
    xhttp.open(method, url, true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhttp.onreadystatechange = () => {

      if (xhttp.readyState == 4) {
        lang_id = Number(xhttp.responseText);
        console.log(lang_id);
      }

    }

    xhttp.send(post_data);

  }

  httpRequest("POST", "get_lang_id.php");

}

function selectText() {
  
  setLangId();

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
         
          //experiment
          let tt_btns = document.querySelectorAll('.tooltip');

          tt_btns.forEach(tt_btn => {
            tt_btn.onclick = showAnnotate;
          });
           //experiment

          loadingbutton.remove();
        }
     
      }

      xhttp.send(post_data);

}

  httpRequest("POST", "retrieve_text.php");

}

function selectText_splitup(dt_start, dt_end, page_nos, page_cur) {
  

  let loadingbutton = document.createElement('div');
  loadingbutton.innerHTML = "Loading...";
  loadingbutton.id = 'loadingbutton';
  document.getElementById('spoofspan').after(loadingbutton);

  let textselect_value = document.getElementById('textselect').value;
  let post_data = "textselect="+textselect_value+"&dt_start="+dt_start+"&dt_end="+dt_end+"&page_cur="+page_cur;
  console.log(post_data);
  const httpRequest = (method, url) => {

     
      let textbody = document.getElementById('textbody');

      const xhttp = new XMLHttpRequest();
      xhttp.open(method, url, true);
      xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

      xhttp.onreadystatechange = () => {
      
         
    
        if(xhttp.readyState == 4) {
          textbody.innerHTML = xhttp.responseText;
          //experiment
          let tt_btns = document.querySelectorAll('.tooltip');

          tt_btns.forEach(tt_btn => {
            tt_btn.onclick = showAnnotate;
          });
           //experiment
          loadingbutton.remove();
          if(page_cur > 1) {
            let title = document.getElementById("title");
            title.scrollIntoView();
          }
        }
     
      }

      xhttp.send(post_data);

}

  httpRequest("POST", "retrieve_text_splitup.php");
  
 
}


function progressBar(word_count) {

  let loading_bar_percentage = document.getElementById("percent");

  const httpRequest = (method, url) => {

    const xhttp = new XMLHttpRequest();
    xhttp.open(method, url, true);
    xhttp.responseType = 'text';
    //xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhttp.onload = () => {

      if(xhttp.readyState == 4) {

        let words_progress = Number(xhttp.responseText);
        let percent = ((words_progress*100)/word_count).toFixed(2);
        loading_bar_percentage.innerHTML = `${percent}%`;
        //console.log("progress_bar.php triggered");
      }

    }
    xhttp.send();
  }
  httpRequest("GET", "progress_bar.php");

  setTimeout(`progressBar(${word_count})`, 100); //setTimeout() executes the function in its first argument after the number of milliseconds in its second argument, so this progressBar function gets called every 0.1 seconds forever until the page reloads when the text is fully loaded
 
} 



function loadText() {

  let newtext_raw = document.getElementById('newtext').value;
  if(newtext_raw == '') {return; }
  let words = newtext_raw.split(' ');
  let word_count = words.length;

  let newtext = encodeURIComponent(newtext_raw);
 
  let text_title = encodeURIComponent(document.getElementById('text_title').value);
  let langselect = document.getElementById('langselect').value;

  

  let loadingbutton = document.createElement('div');
  loadingbutton.innerHTML = 'Processing text: <span id="percent">0.00%</span>';
  loadingbutton.id = 'loadingbutton';
  document.getElementById('spoofspan').after(loadingbutton);

  
  
  const httpRequest = (method, url) => {

   let send_data = "new_text="+newtext+"&text_title="+text_title+"&langselect="+langselect;

   const xhttp = new XMLHttpRequest();
   xhttp.open(method, url, true);
  // xhttp.responseType = 'json';
  xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

  xhttp.onload = () => {
    console.log("sent");
  // console.log(xhttp.responseText);
    if(xhttp.readyState == 4)  {
      loadingbutton.remove(); //not really needed
      location.reload(); /* window.open("update_db.php"); */ 
    }
  }
   xhttp.send(send_data);
 }

 httpRequest("POST", "update_db.php"); //SHOULD BE update_db.php
 // for(let x = 150; x > 0; x--) {
 progressBar(word_count);
 
}



function clearTable() {
  
  let loadingbutton = document.createElement('div');
  loadingbutton.innerHTML = "Loading...";
  loadingbutton.id = 'loadingbutton';
  document.getElementById('spoofspan').after(loadingbutton);

  const httpRequest = (method, url) => {
 
    const xhttp = new XMLHttpRequest();
    xhttp.open(method, url, true);
   // xhttp.responseType = 'json';
   // xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    
    xhttp.onload = () => {
      console.log("sent");
     // console.log(xhttp.responseText);
     if(xhttp.readyState == 4) {
      loadingbutton.remove(); 
      location.reload();
    }
  }
    xhttp.send();
  }
 
  httpRequest("POST", "clear_table.php");
 }

 function deleteText() {
  let textselect_value = document.getElementById('textselect').value;
  if(textselect_value == 0) { return;}
  
  let loadingbutton = document.createElement('div');
  loadingbutton.innerHTML = "Loading...";
  loadingbutton.id = 'loadingbutton';
  document.getElementById('spoofspan').after(loadingbutton);

  
  let post_data = "textselect="+textselect_value;

  const httpRequest = (method, url) => {
 
    const xhttp = new XMLHttpRequest();
    xhttp.open(method, url, true);
   // xhttp.responseType = 'json';
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    
    xhttp.onload = () => {
      console.log(post_data);
     // console.log(xhttp.responseText);
     if(xhttp.readyState == 4) {
      loadingbutton.remove(); 
      location.reload();
    }
  }
    xhttp.send(post_data);
  }
 
  httpRequest("POST", "delete_text.php");

 }


 function texts_link() {
   window.location = "/Kazakh_testing/text_viewer.php";
 }
 function add_text_link() {
  window.location = "/Kazakh_testing/add_texts.php";
}

function showDeletion() {
  let spoofspan_display = document.getElementById("spoofspan").style.display;
  if(spoofspan_display == "inline") {
    document.getElementById("spoofspan").style.display = "none";
    document.getElementById("deletion").style.visibility = "visible";
  }
  else if(spoofspan_display == "none"){
    document.getElementById("spoofspan").style.display = "inline";
    document.getElementById("deletion").style.visibility = "hidden";
  }

}

const noun_pos = '<span id="pos_tag_noun" class="pos_tag" onclick="selectPoS()">noun</span>';
const verb_pos = '<span id="pos_tag_verb" class="pos_tag" onclick="selectPoS()">verb</span>';
const adj_pos = '<span id="pos_tag_adj" class="pos_tag" onclick="selectPoS()" title="adjective">adject.</span>';
const adverb_pos = '<span id="pos_tag_adverb" class="pos_tag" onclick="selectPoS()">adverb</span>';
const prep_pos = '<span id="pos_tag_prep" class="pos_tag" title="preposition" onclick="selectPoS()">prep.</span>';
const conj_pos = '<span id="pos_tag_conj" class="pos_tag" title="conjunction" onclick="selectPoS()">conj.</span>';
const part_pos = '<span id="pos_tag_part" class="pos_tag" title="particle/interjection" onclick="selectPoS()">part.</span>';

const deadFunc = function () {
  let pos_selects = document.querySelectorAll('.pos_tag_select');
  pos_selects.forEach(pos_select => {
    pos_select.remove();
  });
  this.onclick = selectPoS;
};


const changePoS = function () {
  switch (this.id) {
    case "noun_pos":
      document.getElementById('pos_tag_box').innerHTML = noun_pos;
      break;
    case "verb_pos":
      document.getElementById('pos_tag_box').innerHTML = verb_pos;
      break;
    case "adj_pos":
      document.getElementById('pos_tag_box').innerHTML = adj_pos;
      break;  
    case "adverb_pos":
      document.getElementById('pos_tag_box').innerHTML = adverb_pos;
      break;
    case "prep_pos":
      document.getElementById('pos_tag_box').innerHTML = prep_pos;
      break;
    case "conj_pos":
      document.getElementById('pos_tag_box').innerHTML = conj_pos;
      break;  
    case "part_pos":
      document.getElementById('pos_tag_box').innerHTML = part_pos;
      break;
  }
};

const selectPoS = function () {
  let pos_tag_current_id = document.querySelector('.pos_tag').id;

  let pos_tag_select_current = "noun_pos";

  switch (pos_tag_current_id) {
    case "pos_tag_noun":
      pos_tag_select_current = "noun_pos";
      break;
    case "pos_tag_verb":
      pos_tag_select_current = "verb_pos";
      break;
    case "pos_tag_adj":
      pos_tag_select_current = "adj_pos";
      break;  
    case "pos_tag_adverb":
      pos_tag_select_current = "adverb_pos";
      break;
    case "pos_tag_prep":
      pos_tag_select_current = "prep_pos";
      break;
    case "pos_tag_conj":
      pos_tag_select_current = "conj_pos";
      break;  
    case "pos_tag_part":
      pos_tag_select_current = "part_pos";
      break;
  }

  let frag = document.createRange().createContextualFragment('<span id="noun_pos" class="pos_tag_select">noun</span><span id="verb_pos" class="pos_tag_select">verb</span><span id="adj_pos" class="pos_tag_select" title="adjective">adject.</span><span id="adverb_pos" class="pos_tag_select">adverb</span><span id="prep_pos" class="pos_tag_select" title="preposition">prep.</span><span id="conj_pos" class="pos_tag_select" title="conjunction">conj.</span><span id="part_pos" class="pos_tag_select" title="particle/interjection">part.</span>');

  document.getElementById(pos_tag_current_id).after(frag);
  document.getElementById(pos_tag_current_id).onclick = deadFunc;

  document.getElementById('noun_pos').onclick = changePoS;
  document.getElementById('verb_pos').onclick = changePoS;
  document.getElementById('adj_pos').onclick = changePoS;
  document.getElementById('adverb_pos').onclick = changePoS;
  document.getElementById('prep_pos').onclick = changePoS;
  document.getElementById('conj_pos').onclick = changePoS;
  document.getElementById('part_pos').onclick = changePoS;

  document.getElementById('pos_tag_box').removeChild(document.getElementById(pos_tag_select_current));

};


let meanings = {};

function showAnnotate(event) {
  let lemma_meaning_no = 1;
  let lemma_id = 0;
  meanings = {};
  let display_word = event.target;
  let tokno_current = event.target.dataset.tokno;
 

  let previous_selections = document.querySelectorAll('.tooltip_selected');
  previous_selections.forEach(previous_selection => {
    previous_selection.classList.add("tooltip");
    previous_selection.classList.remove("tooltip_selected");
  });
  display_word.classList.add("tooltip_selected");
  display_word.classList.remove("tooltip");

  let word_engine_id = event.target.dataset.word_engine_id;
  console.log(word_engine_id);

  function switchMeaningAJAX() {
    const httpRequest = (method, url) => {
      let send_data = "lemma_id="+lemma_id+"&lemma_meaning_no="+lemma_meaning_no;

      const xhttp = new XMLHttpRequest();
      xhttp.open(method, url, true);
      xhttp.responseType = 'json';
      xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

      xhttp.onload = () => {
        if(xhttp.readyState == 4) {
          let json_response = xhttp.response;
          console.log(json_response);
          meanings[lemma_meaning_no] = json_response.lemma_textarea_content;
          document.getElementById("lemma_textarea").innerHTML = meanings[lemma_meaning_no];
        }
      }
      xhttp.send(send_data);
    }
    httpRequest("POST", "retrieve_meanings.php");
  }

  const switchMeaning = function (event) {
    let grey_arrows = document.querySelectorAll('.nav_arrow_deactiv');
    grey_arrows.forEach(grey_arrow => {
      grey_arrow.classList.add("nav_arrow");
      grey_arrow.classList.remove("nav_arrow_deactiv");
    });
  
        
    let bool_uparrow = event.target.id == "meaning_rightarrow" ? true : false;
    if(bool_uparrow && lemma_meaning_no < 10) {
      lemma_meaning_no++;
    }
    else if(bool_uparrow == false && lemma_meaning_no > 1) {
      lemma_meaning_no--;
    }
    document.getElementById("number").innerHTML = lemma_meaning_no;
    if(lemma_meaning_no == 10) {
      document.getElementById("meaning_rightarrow").classList.add("nav_arrow_deactiv");
      document.getElementById("meaning_rightarrow").classList.remove("nav_arrow");
    }
    if(lemma_meaning_no == 1) {
      document.getElementById("meaning_leftarrow").classList.add("nav_arrow_deactiv");
      document.getElementById("meaning_leftarrow").classList.remove("nav_arrow");
    }
    if(lemma_id != 0 && meanings[lemma_meaning_no] === undefined) {
      switchMeaningAJAX();
    }
    else if(lemma_id != 0) {
      document.getElementById("lemma_textarea").innerHTML = meanings[lemma_meaning_no];
    }
  };

  const disRegard = function () {
    const httpRequest = (method, url) => {


      let send_data = "word_engine_id="+word_engine_id+"&tokno_current="+tokno_current;

      const xhttp = new XMLHttpRequest();
      xhttp.open(method, url, true);
      xhttp.responseType = 'json';
      xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

      xhttp.onload = () => {
        console.log("sent");
        // console.log(xhttp.responseText);
        if (xhttp.readyState == 4) {
          let json_response = xhttp.response;
          console.log(json_response);
          console.log(json_response.word); //the keys in the JSON string have to be in quotes, but as members of the json object they are just retrievable as if the key-name were a variable name. It also works as json_response["word"].
         // display_word.style.color = "#cbd9f4";
        }
      }
      xhttp.send(send_data);
    }

    httpRequest("POST", "json_test.php");
  };

  const lemmaRecord = function () {
    const httpRequest = (method, url) => {

      let lemma_form = encodeURIComponent(document.getElementById('lemma_tag').innerHTML);
      let lemma_meaning = encodeURIComponent(document.getElementById('lemma_textarea').value);

      let send_data = "word_engine_id="+word_engine_id+"&lemma_form="+lemma_form+"&lemma_meaning="+lemma_meaning+"&lemma_meaning_no="+lemma_meaning_no+"&lang_id="+lang_id+"&tokno_current="+tokno_current;

      const xhttp = new XMLHttpRequest();
      xhttp.open(method, url, true);

      xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

      xhttp.onload = () => {
        console.log("sent");
        // console.log(xhttp.responseText);
        if (xhttp.readyState == 4) {
          console.log("Lemma updated");
          document.getElementById('annot_box').remove();
          display_word.classList.add("tooltip");
          display_word.classList.remove("tooltip_selected");
         // display_word.classList.add("lemma_set");
          let dataselectorstring = '[data-word_engine_id="'+word_engine_id+'"]';

          let current_words = document.querySelectorAll(dataselectorstring);
          current_words.forEach(current_word => {
            current_word.classList.add("lemma_set");
          });
        }
      }
      xhttp.send(send_data);
    }

    httpRequest("POST", "lemma_record.php");
  };

  const httpRequest = (method, url) => {

    let send_data = "word_engine_id="+word_engine_id+"&tokno_current="+tokno_current;
 
    const xhttp = new XMLHttpRequest();
    xhttp.open(method, url, true);
    xhttp.responseType = 'json';
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
 
    xhttp.onload = () => {
      console.log("sent");
    // console.log(xhttp.responseText);
      if(xhttp.readyState == 4)  {

        let json_response = xhttp.response;
        let lemma_tag_content = json_response.lemma_tag_content;
        let lemma_textarea_content = json_response.lemma_textarea_content;
        lemma_meaning_no = Number(json_response.lemma_meaning_no);
        lemma_id = Number(json_response.lemma_id);

        if(lemma_meaning_no != 0) {
          meanings[lemma_meaning_no] = lemma_textarea_content;
        }
        else {
          lemma_meaning_no = 1;
        }
        

        if(document.getElementById('annot_box') != null) {
          let annot_box = document.getElementById('annot_box');
          annot_box.remove();
        }

        let annot_box = document.createRange().createContextualFragment('<div id="annot_box" data-word_engine_id="'+word_engine_id+'"><div id="annot_topbar" ondblclick="makeDraggable()"><span id="close_button" onclick="delAnnotate()">Close</span><span id="disregard_button" title="Make this word unannotatable and delete it from the WordEngine">Disregard</span></div><div id="annot"><div id="left_column"><span id="lemma_box" class="box">Lemma translation</span><span id="context_box" class="box">Context translation</span><span id="morph_box" class="box">Morphology</span><span id="multiword_box" class="box">Multiword</span><span id="accent_box" class="box">Accentology</span></div><div id="right_column"><div id="right_header"><div id="lemma_tag" role="textbox" contenteditable>'+lemma_tag_content+'</div></div><div id="right_body"><textarea id="lemma_textarea" autocomplete="off">'+lemma_textarea_content+'</textarea></div><div id="right_footer"><span id="pos_tag_box"></span><div id="meaning_no_box"><div id="meaning_leftarrow" class="nav_arrow"><</div><div id="meaning_no">Meaning <span id="number">'+lemma_meaning_no+'</span></div><div id="meaning_rightarrow" class="nav_arrow">></div></div><div id="save_button">Save</div></div></div></div></div>');

        document.getElementById('spoofspan').after(annot_box);
        if(lemma_meaning_no == 1) {
          document.getElementById("meaning_leftarrow").classList.add("nav_arrow_deactiv");
          document.getElementById("meaning_leftarrow").classList.remove("nav_arrow");
        }
        else if (lemma_meaning_no == 10) {
          document.getElementById("meaning_rightarrow").classList.add("nav_arrow_deactiv");
          document.getElementById("meaning_rightarrow").classList.remove("nav_arrow");
        }

        document.getElementById('disregard_button').onclick = disRegard;
        document.getElementById('save_button').onclick = lemmaRecord;
        document.getElementById('meaning_leftarrow').onclick = switchMeaning;
        document.getElementById('meaning_rightarrow').onclick = switchMeaning;

        let current_box = document.getElementById('lemma_box');

        let left_column = document.getElementById('left_column');

        left_column.onclick = function (event) {
          let target = event.target;
          if (target.className != 'box') return;

          selectBox(target);
        };

        function selectBox(box) {
          if (current_box) {
            current_box.style.color = "rgb(0 255 34 / 41%)";
            current_box.style.backgroundColor = "#172136";
          }
          current_box = box;
          document.getElementById('lemma_textarea').focus();
          current_box.style.color = "rgb(0, 255, 34)";
          current_box.style.backgroundColor = "#040a16";
        }
        //below does the same as above but less efficiently
      /* const panelSelect = function () {

          const boxes = document.querySelectorAll('.box');
          boxes.forEach(box => {
            box.style.color = "rgb(0 255 34 / 41%)";
            box.style.backgroundColor = "#172136";
            box.style.border = "none";
          });
          document.getElementById('lemma_textarea').focus();
          this.style.color = "rgb(0, 255, 34)";
          this.style.backgroundColor = "#040a16";

        }; 

        document.getElementById('lemma_box').onclick = panelSelect;
        document.getElementById('context_box').onclick = panelSelect;
        document.getElementById('morph_box').onclick = panelSelect;
        document.getElementById('multiword_box').onclick = panelSelect;
        document.getElementById('accent_box').onclick = panelSelect;  */

        document.getElementById('pos_tag_box').innerHTML = noun_pos;

        //document.getElementById('lemma_textarea').focus();
          
      }
     
   }
    xhttp.send(send_data);
  }
 
  httpRequest("POST", "retrieve_engword.php");

}

const delAnnotate = function () {
  let annot_box = document.getElementById('annot_box');

  let previous_selections = document.querySelectorAll('.tooltip_selected');
  previous_selections.forEach(previous_selection => {
    previous_selection.classList.add("tooltip");
    previous_selection.classList.remove("tooltip_selected");
  });
  meanings = {};
  annot_box.remove();
};

const makeDraggable = function () {
  dragAnnotBox(document.getElementById("annot_box"));

  function dragAnnotBox(elmnt) {
    var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
    if (document.getElementById("annot_topbar")) {
      /* if present, the header is where you move the DIV from:*/
      document.getElementById("annot_topbar").onmousedown = dragMouseDown;
    } else {
      /* otherwise, move the DIV from anywhere inside the DIV:*/
      elmnt.onmousedown = dragMouseDown;
    }

    function dragMouseDown(e) {
      e = e || window.event;
      e.preventDefault();
      // get the mouse cursor position at startup:
      pos3 = e.clientX;
      pos4 = e.clientY;
      document.onmouseup = closeDragElement;
      // call a function whenever the cursor moves:
      document.onmousemove = elementDrag;
    }

    function elementDrag(e) {
      e = e || window.event;
      e.preventDefault();
      // calculate the new cursor position:
      pos1 = pos3 - e.clientX;
      pos2 = pos4 - e.clientY;
      pos3 = e.clientX;
      pos4 = e.clientY;
      // set the element's new position:
      elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
      elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
    }

    function closeDragElement() {
      /* stop moving when mouse button is released:*/
      document.onmouseup = null;
      document.onmousemove = null;
    }
  }

  document.getElementById('annot_topbar').style.cursor = 'move';

};



/*
  window.addEventListener("keydown", event => {
    if (event.repeat) {return;}
    if (event.key == "c") {
      document.getElementById("tt_styles").href = "tooltip_edit.css";
    }
  });

  window.addEventListener("keyup", event => {
    var spanDisplay = spoofspan.style.display;
    if (event.key == "c") {

      if(spanDisplay == 'inline') { document.getElementById("tt_styles").href = "tooltip_eng_style_2.css"; }
      else { document.getElementById("tt_styles").href = "tooltip_none_style_2.css";}
    }
  }); */




