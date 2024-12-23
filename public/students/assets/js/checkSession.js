function handleVisibilityChange() {
    const testId = getTestIdFromElement(); // Get the current test ID
    console.log("Visibility changed:", document.visibilityState, "Test ID:", testId);
    if (!testId) {
        console.error("Test ID is not available.");
        return;
    }

    const urlPrefix = `/students/tests/${testId}/session/`; // Construct URL with test ID
    const action = document.visibilityState === 'visible' ? 'start' : 'end';
    const url = urlPrefix + action;
    fetch(url, {
        method: "POST",
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => console.log(data))
    .catch(error => console.error(error));
}

// Set up the event listener for visibility changes
document.addEventListener("visibilitychange", handleVisibilityChange);

// Call the function immediately to handle the session start when the page loads
handleVisibilityChange();

function getTestIdFromElement() {
    const testContainer = document.getElementById('testContainer');
    return testContainer ? testContainer.getAttribute('data-test-id') : null;
}
