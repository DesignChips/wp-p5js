window.onload = function() {
  var editor = ace.edit("editor");
  editor.setTheme("ace/theme/monokai");
  editor.getSession().setMode("ace/mode/javascript");
  document.getElementById('p5js-script').value = editor.getValue();
  editor.on("change", function(){
    document.getElementById('p5js-script').value = editor.getValue();
  });
};
