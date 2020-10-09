var profileModal = document.getElementById("profilemodaldiv");

function openProfileModal()
{
	profileModal.style.display = "block";
}

function closeProfileModal()
{
	profileModal.style.display = "none";
}

window.onclick = function(event) {
  if (event.target == profileModal) {
    profileModal.style.display = "none";
  }
}