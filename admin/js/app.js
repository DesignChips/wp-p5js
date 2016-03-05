jQuery(document).ready(function() {
  jQuery('#tabmenu').tabs();

  var editorHTML = ace.edit("editor-html");
  editorHTML.setTheme("ace/theme/monokai");
  editorHTML.getSession().setMode("ace/mode/html");
  document.getElementById('p5js-html').value = editorHTML.getValue();
  editorHTML.on("change", function(){
    document.getElementById('p5js-html').value = editorHTML.getValue();
  });

  var editorCSS = ace.edit("editor-css");
  editorCSS.setTheme("ace/theme/monokai");
  editorCSS.getSession().setMode("ace/mode/css");
  document.getElementById('p5js-css').value = editorCSS.getValue();
  editorCSS.on("change", function(){
    document.getElementById('p5js-css').value = editorCSS.getValue();
  });

  var editorSetup = ace.edit("editor-setup");
  editorSetup.setTheme("ace/theme/monokai");
  editorSetup.getSession().setMode("ace/mode/javascript");
  document.getElementById('p5js-setup').value = editorSetup.getValue();
  editorSetup.on("change", function(){
    document.getElementById('p5js-setup').value = editorSetup.getValue();
  });

  var editorDraw = ace.edit("editor-draw");
  editorDraw.setTheme("ace/theme/monokai");
  editorDraw.getSession().setMode("ace/mode/javascript");
  document.getElementById('p5js-draw').value = editorDraw.getValue();
  editorDraw.on("change", function(){
    document.getElementById('p5js-draw').value = editorDraw.getValue();
  });


});
