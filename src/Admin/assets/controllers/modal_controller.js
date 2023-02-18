import { Controller } from '@hotwired/stimulus'
export default class extends Controller {
  static targets = ['modal', 'modalcontent', 'modaltitle', 'modalbody', 'modalsuccessbutton', 'modalcancelbutton', 'modalbackground', 'buttonopenmodal']

  connect () {
    this.element[this.identifier] = this
  }

  openModal (event) {
    this.modalTarget.classList.add('is-active')
  }

  closeModal (event) {
    let target = event.target
    if(null === target.getAttribute('disabled')){
      this.modalTarget.classList.remove('is-active')
    }
  }
}
