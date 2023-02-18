/* Set the width of the sidebar to 300px and the left margin of the page content to 300px */
function openNav (state) {
  // document.getElementById('sidebar').style.width = '250px'
  // document.getElementById('main').style.marginLeft = '250px'
  document.getElementById('sidebar').classList.add('sidebar-2')
  document.getElementById('sidebar').classList.remove('sidebar-1')
  document.getElementById('main').classList.add('is-10')
  document.getElementById('main').classList.remove('is-11')

  let els = document.getElementsByClassName('menu-text')
  Array.prototype.forEach.call(els, function (el) {
    el.classList.remove('is-hidden')
  })

  state.currentState = 'open'
}

/* Set the width of the sidebar to 0 and the left margin of the page content to 0 */
function closeNav (state) {
  // document.getElementById('sidebar').style.width = '40px'
  // document.getElementById('main').style.marginLeft = '17%'
  document.getElementById('sidebar').classList.remove('sidebar-2')
  document.getElementById('sidebar').classList.add('sidebar-1')
  document.getElementById('main').classList.remove('is-10')
  document.getElementById('main').classList.add('is-11')

  let els = document.getElementsByClassName('menu-text')
  Array.prototype.forEach.call(els, function (el) {
    el.classList.add('is-hidden')
  })

  state.currentState = 'closed'
}

let openCloseBtn = document.getElementById('openCloseBtn')
let state = {
  currentState: 'closed'
}

openNav(state)
openCloseBtn.addEventListener('click', function (event) {
  if (state.currentState === 'open') {
    closeNav(state)
  } else if (state.currentState === 'closed') {
    openNav(state)
  }
})
