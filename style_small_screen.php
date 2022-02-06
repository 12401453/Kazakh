
    <style>

   
   
   @font-face { font-family: LiberationSerif; src: url('LiberationSerif-Regular.ttf'); }
      p1 {
         font-family: LiberationSerif; font-size: 18px; color: #cbcbc3;

      }

#main_text {
  display: flex;
  flex-flow: column nowrap;
  justify-content: space-evenly;
  align-items: flex-start;

 /* margin-left: 7.3%;
  margin-right: 7.3%;
  margin-bottom: 25px; */
   background-color: #172136;
  padding: 10px;
}

#textbody {
 font-size: 15px;
}

#whole_text {
  display: flex;
  flex-flow: column nowrap;
  justify-content: space-evenly;
  align-items: flex-start;
}

.chunk {
  white-space: nowrap;
}
#new_text{
 /* margin-left: 7.3%;
  margin-right: 7.3%; */
  background-color: #172136;
  padding: 10px;
}
#tt_button {
 
  background-color: #071022;
  border-style: solid;
  border-radius: 2px;
  padding: 3px;
  z-index: 1;
  color: #cbcbc3;
  font-family: Calibri;
  font-size: 13px;
}

#select_button {

  background-color: #071022;
  border-style: solid;
  border-radius: 2px;
  padding: 4px;
  z-index: 1;
  color: #cbcbc3;
  font-family: Calibri;
  font-size: 13px;

}

#lang_button {
  position: absolute;
  background-color: #071022;
 /* border-style: solid; */
  border-radius: 2px;
  padding: 6px; 
  z-index: 1;
  color: #cbcbc3;
  font-family: Calibri;
  font-size: 14px;

}

#textselect, #langselect {
  background-color: /* #172136; */ #071022;
 /* border-style: solid;
  border-radius: 2px; 
  padding: 3px;  */
  z-index: 1;
  color: #cbcbc3;
  font-family: Calibri;
  font-size: 13px;
  min-width: 75px;

} 

#newtext {
  position: flex;
  width: 70%;
  height: 150px;
  border-style: solid;
  border-radius: 4px;
  background-color: #071022;
  color: #cbcbc3;
  text-indent: 0%;
  font-family: Calibri;
  font-size: 14px;
}

#text_title {
  position: flex;
  width: 70%;
  height: 38px;
  border-style: solid;
  border-radius: 4px;
  background-color: #071022;
  color: #cbcbc3;
  text-indent: 0%;
  font-size: 18px;
  font-family: Calibri;
}

.submit_btn {
  border-style: solid;
  padding: 4px;
  border-radius: 2px;
  background-color: #071022;
  color: #cbcbc3;
  font-family: Calibri;
  top: 8px;
  position:relative;
  font-size: 12px;
  left: 8px;
  cursor: pointer;
}

.submit_btn:hover {
  background-color: #040a16;
} 

#title {
  font-size: 19px;
  font-family: LiberationSerif;
  color: rgb(252, 119, 119);
  text-align: center;
  margin-left: 30px;
  margin-right: 30px;
  position: flex;
}

#loadingbutton {
  position: fixed;
  left: 0;
  bottom: 0;

  background-color: #071022;
  border-style: solid;
  border-radius: 2px;
  padding: 5px;
  z-index: 1;
  color: #cbcbc3;
  font-family: Calibri;
  font-size: 34px;

}


</style>
