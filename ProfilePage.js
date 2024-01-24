document.addEventListener("DOMContentLoaded", function () {
    // Function to handle file input change event for profile page
    document.getElementById("profile-image-input").addEventListener("change", function (event) {
        handleImageChange(event, "profile-image");
        updateSidebarProfileIcon("profile-image");
    });

    // Function to handle "Change Image" button click event
    document.getElementById("change-image").addEventListener("click", function () {
        document.getElementById("profile-image-input").click();
    });

    // Function to handle save button click event
    document.getElementById("save-button").addEventListener("click", function () {
        updateSidebarName();
    });
});

// Function to handle image change and update the specified image element
function handleImageChange(event, imageElementId) {
    const fileInput = event.target;
    const imageElement = document.getElementById(imageElementId);

    if (fileInput.files && fileInput.files[0]) {
        const reader = new FileReader();

        reader.onload = function (e) {
            imageElement.src = e.target.result;
            updateSidebarProfileIcon(imageElementId); // Update sidebar icon
        };

        reader.readAsDataURL(fileInput.files[0]);
    }
}

// Function to update the sidebar profile icon with the selected image
function updateSidebarProfileIcon(profileImageId) {
    const profileImageElement = document.getElementById(profileImageId);
    const sidebarProfileImageElement = document.getElementById("sidebar-profile-image");

    // Copy the source of the profile image to the sidebar profile image
    sidebarProfileImageElement.src = profileImageElement.src;
}

// Function to update the first name and last name on the sidebar
function updateSidebarName() {
    const firstName = document.getElementById("first-name").value;
    const lastName = document.getElementById("last-name").value;

    const sidebarFirstNameElement = document.getElementById("sidebar-first-name");
    const sidebarLastNameElement = document.getElementById("sidebar-last-name");

    // Update the sidebar first name and last name
    sidebarFirstNameElement.textContent = firstName;
    sidebarLastNameElement.textContent = lastName;
}








