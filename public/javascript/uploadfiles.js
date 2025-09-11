  function previewFile(input) {
    const file = input.files[0];
    const uploadBox = input.closest(".upload-box");

    if (!file) return;

    // Remove old preview
    uploadBox.querySelectorAll(".upload-preview").forEach(el => el.remove());

    let preview;

    if (file.type.startsWith("image/")) {
      const reader = new FileReader();
      reader.onload = function (e) {
        preview = document.createElement("label"); // ✅ make preview a label
        preview.setAttribute("for", input.id);     // ✅ link back to input
        preview.className = "upload-preview";
        preview.style.backgroundImage = `url('${e.target.result}')`;
        preview.innerHTML = `<div class="bg-dark bg-opacity-50 p-2 rounded w-100">
                              <small>${file.name}</small>
                            </div>`;
        uploadBox.appendChild(preview);
      };
      reader.readAsDataURL(file);
    } else if (file.type === "application/pdf") {
      preview = document.createElement("label");  // ✅ make preview clickable
      preview.setAttribute("for", input.id);
      preview.className = "upload-preview bg-pdf";
      preview.innerHTML = `<i class="bi bi-file-earmark-pdf fs-1 text-danger"></i>
                           <small>${file.name}</small>`;
      uploadBox.appendChild(preview);
    }
  }