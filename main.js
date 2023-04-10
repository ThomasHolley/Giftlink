// main.js
document.getElementById("search-form").addEventListener("submit", function(event) {
    event.preventDefault();

    var searchInput = document.getElementById("search");
    var searchResults = document.getElementById("search-results");

    var xhr = new XMLHttpRequest();
    xhr.open("GET", "search.php?search=" + encodeURIComponent(searchInput.value), true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            searchResults.innerHTML = xhr.responseText;
            searchResults.style.display = xhr.responseText.trim() !== "" ? "block" : "none";
        }
    };
    xhr.send();
});

document.getElementById("search").addEventListener("input", function() {
    var searchResults = document.getElementById("search-results");
    if (this.value.trim() === "") {
        searchResults.style.display = "none";
    }
});

$("#friend-request-form").on("submit", function(event) {
    event.preventDefault(); // Empêche la soumission normale du formulaire
  
    $.ajax({
      url: $(this).attr("action"),
      type: "POST",
      data: $(this).serialize(),
      success: function(response) {
        showPopup(response); // Affiche la réponse dans une pop-up
      },
      error: function() {
        showPopup("Erreur lors de l'envoi de la demande d'ami.");
      }
    });
  });
  function showPopup(message) {
    let popup = $("<div class='popup'></div>").text(message);
    $("body").append(popup);
  
    setTimeout(function() {
      popup.remove();
    }, 5000);
  }
  document.addEventListener('DOMContentLoaded', () => {
    attachFriendRequestFormHandlers();
});

function attachFriendRequestFormHandlers() {
    document.querySelectorAll('.friend-request-form').forEach(form => {
        form.addEventListener('submit', e => {
            e.preventDefault();
            const submitButton = form.querySelector("button[type='submit']");
            submitButton.disabled = true;

            fetch(form.action, {
                method: form.method,
                body: new FormData(form),
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Network response was not ok: ${response.statusText}`);
                }
                return response.text();
            })
            .then(text => {
                showPopupMessage(text);
                form.style.display = 'none'; // Masquer le formulaire après l'envoi réussi
                const searchResult = form.parentElement;
                searchResult.innerHTML += '<span>Demande d\'ami envoyée</span>'; // Afficher le message de confirmation
                submitButton.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                submitButton.disabled = false;
            });
        });
    });
}
function sendFriendRequest(event, form) {
    event.preventDefault(); // Empêcher le rechargement de la page lors de la soumission du formulaire

    const submitButton = form.querySelector("button[type='submit']");
    submitButton.disabled = true;

    fetch(form.action, {
        method: form.method,
        body: new FormData(form),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Network response was not ok: ${response.statusText}`);
        }
        return response.text();
    })
    .then(text => {
        showPopupMessage(text);
        form.style.display = 'none'; // Masquer le formulaire après l'envoi réussi
        const searchResult = form.parentElement;
        searchResult.innerHTML += '<span>Demande d\'ami envoyée</span>'; // Afficher le message de confirmation
        submitButton.disabled = false;
    })
    .catch(error => {
        console.error('Error:', error);
        submitButton.disabled = false;
    });
}
document.addEventListener('submit', async (e) => {
    if (e.target.matches('.friend-request-form')) {
        e.preventDefault();

        const formData = new FormData(e.target);
        const response = await fetch('send_friend_request.php', {
            method: 'POST',
            body: formData,
        });

        const message = await response.text();
        const messageContainer = document.getElementById('message-container');
        const messageElement = document.createElement('div');
        messageElement.className = 'message';
        messageElement.textContent = message;

        messageContainer.appendChild(messageElement);

        setTimeout(() => {
            messageElement.remove();
        }, 3000);

        e.target.remove(); // Supprime le formulaire de demande d'ami
    }
});

    