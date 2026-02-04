const avatarInput = document.getElementById('avatar');
const preview = document.getElementById('preview');
const fileLabel = document.getElementById('file-label');

if (avatarInput && preview && fileLabel) {
  avatarInput.addEventListener('change', (e) => {
    const file = e.target.files?.[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = (event) => {
      preview.src = event.target.result;
    };
    reader.readAsDataURL(file);

    fileLabel.innerHTML = `<i class="fas fa-check-circle mr-1 text-success"></i>Selected: ${file.name}`;
  });
}
