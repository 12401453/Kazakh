let text = document.getElementById("newtext").value;
let lang_id = Number(document.getElementById('langselect').value);
let text_words = new Array();
let word_eng_words = new Array();
let space_index = new Array();
let tokens = text.split(' ');
let start_range = 0;
let end_range = 0;
let p = 0;
let tb_copy = "";
let text_word = "";
let word_eng_word = "";
let is_a_word = true;

let x = 0;

for(let token of tokens) {
    p = 0;
   
    while(p != -1) {
        start_range = 0;
        p = token.search(/\P{L}/u);
        if(p == -1) break;
        end_range = p - 1;
        tb_copy = token.slice(start_range, end_range);
        token = token.slice(p);
        
        if(tb_copy.search(/\p{L}/u) != -1) {
            is_a_word = true;
        }
        else {
            is_a_word = false;
        }

        if(is_a_word) {
            text_word = tb_copy;
            if(lang_id == 7) word_eng_word = tb_copy.toLocaleLowerCase('tr');
            else word_eng_word = tb_copy.toLowerCase();
        }
        text_words.push(text_word);
        word_eng_words.push(word_eng_word);
      
       
       
        word_eng_word = "";
        text_word = "";
        console.log(end_range);
    }
    x++;


    space_index.push(text_words.length - 1);
}
