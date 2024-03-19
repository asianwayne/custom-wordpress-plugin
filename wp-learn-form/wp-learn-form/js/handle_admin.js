jQuery(document).ready(($) => {

 if (window.DataTable  !== undefined) {
  new DataTable("#example");

 };

 const showInputsCheckbox = $("#wlf_smtp_server");
 const hiddenInputsDiv = $("#hiddeninputs");

    showInputsCheckbox.on("change", function() {
      if (showInputsCheckbox.prop("checked")) {
        hiddenInputsDiv.show();
      } else {
        $("#smtp_server_name").val('');
        $("#smtp_server_pass").val('');
        hiddenInputsDiv.hide();
      }
  
  });

    if (showInputsCheckbox.prop("checked")) {
        hiddenInputsDiv.show();
      } 
});