const notificationList = document.querySelector(".notiflist");

// Fetch notifications data from the server
async function fetchNotifications() {
  try {
    const response = await fetch(`/notification/user`);
    const data = await response.json();

    if (Array.isArray(data) && data.length > 0) {
      data.forEach((notification) => {
        displayNotification(notification);
      });
    } else {
      displayNotification("No notifications yet, don't lose hope!");
    }
  } catch (error) {
    console.error("Failed to fetch notifications:", error);
    displayNotification(
      "Failed to fetch notifications. Please try again later."
    );
  }
}

// Display a notification in the list
function displayNotification(notification) {
  const listItem = document.createElement("li");
  listItem.classList.add("dropdown-item");

  if (notification.sender) {
    const userLink = document.createElement("a");
    userLink.href = `/profile/${notification.sender.id}`;
    listItem.textContent = notification.content;
    userLink.appendChild(listItem);
    notificationList.appendChild(userLink);
  }
}

fetchNotifications();
