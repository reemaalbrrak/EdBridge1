// Define an array to store the announcements
let announcements = [];

// Define an array to store the users
let users = [
    { username: 'admin', password: 'password', role: 'admin' },
    { username: 'user1', password: 'password', role: 'user' },
    { username: 'user2', password: 'password', role: 'user' }
];

// Function to add a new announcement
function addAnnouncement(subject, course, date) {
    const announcement = {
        id: announcements.length + 1,
        subject,
        course,
        date
    };

    announcements.push(announcement);
    console.log(`Added new announcement: ${subject}`);
    
    // Schedule notification delivery
    scheduleNotification(announcement);
}

// Function to edit an existing announcement
function editAnnouncement(id, subject, course, date) {
    const announcement = announcements.find((item) => item.id === id);

    if (announcement) {
        // Perform edit only if the announcement is not in the past
        if (new Date(date) >= new Date()) {
            announcement.subject = subject;
            announcement.course = course;
            announcement.date = date;

            console.log(`Edited announcement with id: ${id}`);
            
            // Reschedule notification delivery
            scheduleNotification(announcement);
        } else {
            console.log(`Cannot edit announcement with id: ${id}. The date has already passed.`);
        }
    } else {
        console.log(`No announcement found with id: ${id}`);
    }
}

// Function to delete an existing announcement
function deleteAnnouncement(id) {
    const index = announcements.findIndex((item) => item.id === id);

    if (index > -1) {
        // Remove the announcement from the array
        const deletedAnnouncement = announcements.splice(index, 1)[0];
        console.log(`Deleted announcement with id: ${id}`);

        // Cancel scheduled notification delivery
        cancelNotification(deletedAnnouncement);
    } else {
        console.log(`No announcement found with id: ${id}`);
    }
}

// Function to schedule notification delivery for an announcement
function scheduleNotification(announcement) {
    console.log(`Scheduled notification for announcement with id: ${announcement.id}`);
    // Implement your scheduling logic here
}

// Function to cancel scheduled notification delivery for an announcement
function cancelNotification(announcement) {
    console.log(`Cancelled notification for announcement with id: ${announcement.id}`);
    // Implement your cancellation logic here
}

// Function to authenticate a user
function authenticateUser(username, password) {
    const user = users.find((item) => item.username === username && item.password === password);

    if (user) {
        console.log(`Authenticated as ${user.role}`);
        return true;
    }

    console.log('Invalid username or password');
    return false;
}

// Initialize the announcements array with sample data
addAnnouncement("Important Exam Information", "Course 1", "Jan 1, 2022");
addAnnouncement("Reminder", "Course 2", "Jan 2, 2022");
addAnnouncement("Office Hours", "Course 3", "Jan 3, 2022");

// Example usage of the functions
if (authenticateUser('admin', 'password')) {
    editAnnouncement(2, 'Updated Announcement', 'Updated Course', 'Jan 5, 2022');
    deleteAnnouncement(1);
}

console.log(announcements);