/**
 * UI Toasts
 */

'use strict';

(function () {
  // Bootstrap toasts example
  // --------------------------------------------------------------------
  const toastPlacementExample = document.querySelector('.toast-placement-ex'),
    toastPlacementBtn = document.querySelector('#showToastPlacement');
  let selectedType, selectedPlacement, toastPlacement;

  // Dispose toast when open another
  function toastDispose(toast) {
    if (toast && toast._element !== null) {
      if (toastPlacementExample) {
        toastPlacementExample.classList.remove(selectedType);
        DOMTokenList.prototype.remove.apply(toastPlacementExample.classList, selectedPlacement);
      }
      toast.dispose();
    }
  }
  // Placement Button click
  if (toastPlacementBtn) {
    toastPlacementBtn.onclick = function () {
      if (toastPlacement) {
        toastDispose(toastPlacement);
      }
      selectedType = document.querySelector('#selectTypeOpt').value;
      selectedPlacement = document.querySelector('#selectPlacement').value.split(' ');

      toastPlacementExample.classList.add(selectedType);
      DOMTokenList.prototype.add.apply(toastPlacementExample.classList, selectedPlacement);
      toastPlacement = new bootstrap.Toast(toastPlacementExample);
      toastPlacement.show();
    };
  }
})();

$(document).ready(function () {
    $("#copied").on("click", function (e) {
        e.preventDefault(); // Prevent the default action

        // Copy the API key to clipboard
        var apiKey = $(this).text();
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val(apiKey).select();
        document.execCommand("copy");
        $temp.remove();

        // Show toast message
        showToast("API key copied to clipboard!");
    });

    $(".copy-api-key").click(function () {
        // Get the API key from the id of the clicked element
        let apiKey = $(this).attr("id");

        // Create a temporary input to hold the text
        let $temp = $("<input>");
        $("body").append($temp);
        $temp.val(apiKey).select();
        document.execCommand("copy");
        $temp.remove();

        // Optionally show a message to the user
        alert("API Key copied to clipboard!");
    });
});

function showToast(message) {
    var $toast = $('<div class="toast">').text(message);
    $("body").append($toast);
    $toast
        .fadeIn(400)
        .delay(2000)
        .fadeOut(400, function () {
            $(this).remove();
        });
}
