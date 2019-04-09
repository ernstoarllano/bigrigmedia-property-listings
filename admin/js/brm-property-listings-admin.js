window.addEventListener('load', () => {
  const uploadBtn = document.querySelector('.js-upload')
  const clearBtn = document.querySelector('.js-clear')
  const galleryContainer = document.querySelector('.gallery-thumbs')
  const galleryInput = document.querySelector('input[name="listing_gallery[]"]')
  let frame

  // Handle media uploads
  const mediaUpload = () => {
    if (frame) {
      frame.open()

      return
    }

    // Create a new media frame
    frame = wp.media({
      title: 'Listing Gallery',
      button: {
        title: 'Upload'
      },
      multiple: true
    })

    // Open State
    frame.on('open', () => {
      if (galleryInput.value.length > 0) {
        const existingImages = JSON.parse(`[${galleryInput.value}]`)

        existingImages.forEach(existingImage => {
          let attachment = wp.media.attachment(existingImage)

          attachment.fetch()

          frame.state().get('selection').add(attachment ? [attachment] : [])
        })
      }
    })

    // Selection State
    frame.on('select', () => {
      const imageIDs = []
      const selectedAttachments = frame.state().get('selection').map(attachment => { return attachment.toJSON() })

      selectedAttachments.forEach(selectedAttachment => {
        imageIDs.push(selectedAttachment.id)

        if (galleryInput.value.length > 0) {
          const existingImages = JSON.parse(`[${galleryInput.value}]`)
          const combinedImages = existingImages.concat(imageIDs)

          const updatedImages = imageIDs.some(imageID => existingImages.includes(imageID))

          if (updatedImages) {
            galleryInput.value = imageIDs
          } else {
            galleryInput.value = [...new Set(combinedImages)]
          }
        } else {
          galleryInput.value = imageIDs
        }
      })
    })

    frame.open()
  }

  if (uploadBtn) {
    uploadBtn.addEventListener('click', mediaUpload)
  }

  if (clearBtn) {
    clearBtn.addEventListener('click', (e) => {
      e.preventDefault()

      galleryContainer.innerHTML = ''
      galleryInput.value = ''
    })
  }
})