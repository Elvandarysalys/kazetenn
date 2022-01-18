class AdminBody extends React.Component {
  constructor (props) {super(props)}

  render () {
    return <div></div>
  }
}

class MenuAside extends React.PureComponent {
  constructor (props) {super(props)}

  render () {

  }
}

ReactDom.render(<AdminBody/>, document.querySelector('#body'))
ReactDom.render(<MenuAside/>, document.querySelector('#nav-side'))
