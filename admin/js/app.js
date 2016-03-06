jQuery(document).ready(function() {
  jQuery('#tabmenu').tabs();

  var editorHTML = ace.edit("editor-html");
  editorHTML.setTheme("ace/theme/monokai");
  editorHTML.getSession().setMode("ace/mode/html");
  setThemeBgColor(editorHTML, "#editor-html");
  enableAutoComplete(editorHTML);
  document.getElementById('p5js-html').value = editorHTML.getValue();
  editorHTML.on("change", function(){
    document.getElementById('p5js-html').value = editorHTML.getValue();
  });


  var editorCSS = ace.edit("editor-css");
  editorCSS.setTheme("ace/theme/monokai");
  editorCSS.getSession().setMode("ace/mode/css");
  setThemeBgColor(editorCSS, "#editor-css");
  enableAutoComplete(editorCSS);
  document.getElementById('p5js-css').value = editorCSS.getValue();
  editorCSS.on("change", function(){
    document.getElementById('p5js-css').value = editorCSS.getValue();
  });



  var editorSetup = ace.edit("editor-setup");
  editorSetup.setTheme("ace/theme/monokai");
  editorSetup.getSession().setMode("ace/mode/javascript");
  setThemeBgColor(editorSetup, "#editor-setup");
  enableAutoComplete(editorSetup);
  document.getElementById('p5js-setup').value = editorSetup.getValue();
  editorSetup.on("change", function(){
    document.getElementById('p5js-setup').value = editorSetup.getValue();
  });



  var editorDraw = ace.edit("editor-draw");
  editorDraw.setTheme("ace/theme/monokai");
  editorDraw.getSession().setMode("ace/mode/javascript");
  setThemeBgColor(editorDraw, "#editor-draw");
  enableAutoComplete(editorDraw);
  document.getElementById('p5js-draw').value = editorDraw.getValue();
  editorDraw.on("change", function(){
    document.getElementById('p5js-draw').value = editorDraw.getValue();
  });
});

window.onload = function () {
  // var editorHTML = ace.edit("editor-html");
  // var acehtml = jQuery("editor-html").css("background-color");
  // var slice = editorHTML.getTheme().lastIndexOf("/");
  // var themeName = editorHTML.getTheme().slice(slice+1);
  // console.log(getThemeBgColor(themeName));
  // jQuery("#editor-html").css("background-color", getThemeBgColor(themeName));
};

function setThemeBgColor(element, selector) {
  var slice = element.getTheme().lastIndexOf("/");
  var themeName = element.getTheme().slice(slice+1);
  jQuery(selector).css("background-color", getThemeBgColor(themeName));
}

function getThemeBgColor(theme) {
  var themeBgList = {
    "monokai" : "#272822"
  };
  return themeBgList[theme];
}

function enableAutoComplete(aceObj) {
  aceObj.setOptions({
    enableBasicAutocompletion: true,
    enableSnippets: true,
    enableLiveAutocompletion: true
  });
}
