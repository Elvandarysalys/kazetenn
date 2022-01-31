import React from 'react'
import ReactDOM from 'react-dom'

function getFormInfos (fetchUrl) {
  return fetch(fetchUrl).then(function (response) {
    return response.json().then(function (jsonResponse) {
      return JSON.parse(jsonResponse)
    })
  })
}

function addContentToPage (parentId) {
  let data = {}

  for (const key in formData) {
    data[key] = formData[key].value
  }

  let fetchUrl = document.querySelector('#ajaxurl').getAttribute('data-addcontent')
  return fetch(fetchUrl, {
    method: 'POST',
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(data)
  }).then(function (response) {
    return response.json().then(function (jsonResponse) {
      return JSON.parse(jsonResponse)
    })
  })
}

function submitFormInfos (fetchUrl, formData) {
  let data = {}

  for (const key in formData) {
    data[key] = formData[key].value
  }

  return fetch(fetchUrl, {
    method: 'POST',
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(data)
  }).then(function (response) {
    return response.json().then(function (jsonResponse) {
      return JSON.parse(jsonResponse)
    })
  })
}

class ContentForm extends React.Component {
  constructor (props) {
    super(props)
    this.handleChange = this.handleChange.bind(this)
  }

  handleChange (event) {
    let formData = this.state.formData
    formData[event.target.id].value = event.target.value
    this.setState({ formData: formData })
  }

  render () {
    let align = 'admin_children_vertical'
    if (this.props.children.align === 'horizontal') {
      align = 'admin_children_horizontal'
    }
    return (
      <div key={this.props.children.id} style={{ order: this.props.children.blocOrder }}>
        <button value={'master'} onClick={this.addContent}>Ajouter du contenu</button>

        <div className={'field is-grouped'}>
          <div className={'field'} key={'align'}>
            <label className={'label'}>Alignement</label>
            <div className={'control'}>
              <div className={'select'}>
                <select value={this.props.children.align} onChange={this.handleChange}>
                  <option key={0} value={'horizontal'}>Horizontal</option>
                  <option key={1} value={'vertical'}>Vertical</option>
                </select>
              </div>
            </div>
          </div>

          <div className={'field'} key={'content'}>
            <label className={'label'}>content</label>
            <div className={'control'}>
              <input className={'input'} type="text" value={this.props.children.content}
                     onChange={this.handleChange}/>
            </div>
          </div>
        </div>

        <div className={align}>
          {this.props.children.childrens.map(form => {
            return <ContentForm>{form}</ContentForm>
          })}
        </div>
      </div>
    )
  }
}

class PageForm extends React.Component {
  constructor (props) {
    super(props)
    this.state = {
      formData: {},
      ajax_route: ''
    }

    this.handleChange = this.handleChange.bind(this)
    this.handleSubmit = this.handleSubmit.bind(this)
    this.addContent = this.addContent.bind(this)
  }

  async componentDidMount () {
    let fetchUrl
    if (undefined === fetchUrl) {
      fetchUrl = document.querySelector('#ajaxurl').getAttribute('data-ajaxurl')
    }
    const formData = await getFormInfos(fetchUrl)
    if (formData) {
      this.setState({ ajax_route: formData.ajax_route, formData: formData.data })
    }
  }

  addContent (event) {
    event.preventDefault()
    console.log(event.target.value)

    addContentToPage(event.target.value)
  }

  handleChange (event) {
    let formData = this.state.formData
    formData[event.target.id].value = event.target.value
    this.setState({ formData: formData })
  }

  async handleSubmit (event) {
    event.preventDefault()

    let fetchUrl = this.state.ajax_route
    if (undefined === this.state.ajax_route) {
      fetchUrl = document.querySelector('#ajaxurl').getAttribute('data-ajaxurl')
    }

    const result = await submitFormInfos(fetchUrl, this.state.formData)
    if (result) {
      this.setState({ ajax_route: result.ajax_route, formData: result.data })
    }
  }

  render () {
    let fields = []
    let pageId = document.querySelector('#ajaxurl').getAttribute('data-pageid')
    // let childrens = []
    // let prototype = []
    for (const key in this.state.formData) {
      fields.push(this.state.formData[key])
      // if (key === 'children') {
      //   childrens = this.state.formData[key]
      // }
    }

    // for (const key in childrens.prototype) {
    //   prototype.push(childrens.prototype[key])
    // }
    // console.log(prototype)
    if (fields.length === 0) {
      return <div>empty</div>
    } else {
      return (
        <div>
          <span>{this.state.ajax_route}</span>
          <form onSubmit={this.handleSubmit}>
            {fields.map(form => {
              switch (form.type) {
                default:
                case 'text':
                  return <div className={'field'} key={form.id}>
                    <label className={'label'}>{form.name}</label>
                    <div className={'control'}>
                      <input className={'input'} type="text" id={form.name} value={form.value}
                             onChange={this.handleChange}/>
                    </div>
                  </div>

                case 'choice':
                  return <div className={'field'} key={form.id}>
                    <label className={'label'}>{form.name}</label>
                    <div className={'control'}>
                      <div className={'select'}>
                        <select id={form.name} value={form.choice_values[0]} onChange={this.handleChange}>
                          {form.choice_values.map(option => {
                            return <option key={option.key} value={option.value}>{option.label}</option>
                          })}
                        </select>
                      </div>
                    </div>
                  </div>

                case 'collection':
                  if (pageId !== null) {
                    return (
                      <div>
                        <button value={pageId} onClick={this.addContent}>Ajouter du contenu</button>
                        {form.collection_values.map(data => {
                          return (<ContentForm>{data}</ContentForm>)
                        })}
                      </div>
                    )
                  } else {
                    ''
                  }

                case 'hidden':
                  return <div key={form.id}>
                    <input type="hidden" id={form.name} value={form.value} onChange={this.handleChange}/>
                  </div>
              }

            })}
            <input type="submit" value="Submit"/>
          </form>
          {/*<div>*/}
          {/*  <button id={'master'} onClick={this.addContent}>Ajouter du contenu</button>*/}
          {/*  /!*<ContentForm>{prototype}</ContentForm>*!/*/}
          {/*  {childrens.collection_values.map(data => {*/}
          {/*    return (<ContentForm>{data}</ContentForm>)*/}
          {/*  })}*/}
          {/*</div>*/}
        </div>
      )
    }
  }
}

class AdminBody extends React.Component {
  constructor (props) {super(props)}

  render () {
    return (
      <div>
        <PageForm/>
      </div>
    )
  }
}

ReactDOM.render(<AdminBody/>, document.querySelector('#body'))
