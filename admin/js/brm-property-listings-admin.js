window.addEventListener('load', () => {
  const listingContainer = document.querySelector('#listing_gallery .inside')
  const galleryContainer = document.querySelector('.gallery-thumbs')
  const dataInputs = document.querySelectorAll('input[name="listing_gallery[]"]')
  const uploadBtn = document.querySelector('.js-upload')
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
      dataInputs.forEach(dataInput => {
        let attachment = wp.media.attachment(dataInput.value)

        attachment.fetch()

        frame.state().get('selection').add(attachment ? [attachment] : [])
      })
    })

    // Selection State
    frame.on('select', () => {
      const selectedAttachments = frame.state().get('selection').map(attachment => { return attachment.toJSON() })

      selectedAttachments.forEach(selectedAttachment => {
        const thumb = document.createElement('img')
        const input = document.createElement('input')

        thumb.setAttribute('src', selectedAttachment.sizes.w132x132.url)
        thumb.setAttribute('width', selectedAttachment.sizes.w132x132.width)
        thumb.setAttribute('width', selectedAttachment.sizes.w132x132.height)

        input.setAttribute('type', 'hidden')
        input.setAttribute('name', 'listing_gallery[]')
        input.setAttribute('value', selectedAttachment.id)

        galleryContainer.append(thumb)
        listingContainer.append(input)
      })
    })

    frame.open()
  }

  if (uploadBtn) {
    uploadBtn.addEventListener('click', mediaUpload)
  }
})