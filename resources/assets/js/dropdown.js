/**
 * Dropdown component
 */
export function Dropdown(element) {
    const dropdownContent = element.nextElementSibling

    if (!dropdownContent) {
        return
    }

    // toggle dropdown content
    const toggleDropdownContent = (el) => {
        const ariaExpandedVal = el.getAttribute('aria-expanded')
        const fromCls = ['duration-100', 'opacity-0', 'scale-95']
        const toCls = ['duration-75', 'opacity-100', 'scale-100']

        if (ariaExpandedVal === 'true') {
            dropdownContent.classList.add(...fromCls)
            dropdownContent.classList.remove(...toCls)
            el.setAttribute('aria-expanded', 'false')
        }

        if (ariaExpandedVal === 'false') {
            dropdownContent.classList.add(...toCls)
            dropdownContent.classList.remove(...fromCls)
            el.setAttribute('aria-expanded', 'true')
        }
    }

    // click handler
    const onClick = event => {
        toggleDropdownContent(event.currentTarget)
    }

    element.addEventListener('click', onClick)

    // leaving dropdown
    window.onclick = event => {
        const elParent = element.parentElement

        if (!elParent.contains(event.target) && element.getAttribute('aria-expanded') === 'true') {
            toggleDropdownContent(element)
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const toggles = document.querySelectorAll('[data-toggle=dropdown]')

    toggles.forEach(toggle => {
        new Dropdown(toggle)
    })
})