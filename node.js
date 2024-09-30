positionInput.addEventListener('keypress', function(event) {
    if (event.key === 'Enter') {
        moveTaskToPosition(index, positionInput.value);
    }
});
