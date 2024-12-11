const notificationList = document.querySelector(".notiflist");

// Fetch notifications data from the server
async function fetchNotifications() {
  try {
    const response = await fetch(`/notification/user`);
    if (!response.ok) {
      throw new Error(`Server error: ${response.statusText}`);
    }

    const data = await response.json();

    if (Array.isArray(data) && data.length > 0) {
      notificationList.innerHTML = ""; // Clear existing notifications
      data.forEach((notification) => {
        displayNotification(notification);
      });
    } else {
      displayNotification({
        content: "No notifications yet, don't lose hope!",
      });
    }
  } catch (error) {
    console.error("Failed to fetch notifications:", error);
    displayNotification({
      content: "Failed to fetch notifications. Please try again later.",
    });
  }
}

// Display a notification in the list
function displayNotification(notification) {
  const listItem = document.createElement("li");
  listItem.classList.add("dropdown-item");

  if (notification.sender && notification.sender.id && notification.content) {
    const userLink = document.createElement("a");
    userLink.href = `/profile/${notification.sender.id}`;
    userLink.textContent = notification.content;
    listItem.appendChild(userLink);
  } else {
    listItem.textContent = notification.content || "Unknown notification";
  }

  notificationList.appendChild(listItem);
}

fetchNotifications();
