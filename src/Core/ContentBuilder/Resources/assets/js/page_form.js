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

    let contentChildrens = this.props.children.childrens;
    let contents;
    if(undefined === contentChildrens){
      contents = [];
    }else if(contentChildrens.value === undefined){
      contents = [];
    }else{
      contents = contentChildrens.value;
    }

    return (
      <div key={this.props.children.id} style={{ order: this.props.children.blocOrder }}>
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
          { contents.map(form => {
            console.log(form)
            return <ContentForm key={form.id}>{form}</ContentForm>
          })}
        </div>

        <button value={'master'} onClick={this.addContent}>Ajouter du contenu</button>
      </div>
    )
  }
}

function textForm(props){
  return <div className={'field'} key={props.id}>
    <label className={'label'}>{props.name}</label>
    <div className={'control'}>
      <input className={'input'} type="text" id={props.name} value={props.value}
             onChange={this.handleChange}/>
    </div>
  </div>
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
    let title = this.state.formData.title;
    let slug = this.state.formData.slug;
    let parent = this.state.formData.parent;
    let pageContents = this.state.formData.pageContents;
    let token = this.state.formData._token;
    for (const key in this.state.formData) {
      fields.push(this.state.formData[key])
    }

    if (fields.length === 0) {
      return <div>empty</div>
    } else {
      return (
        <div>
          <span>{this.state.ajax_route}</span>
          <form onSubmit={this.handleSubmit}>

            <div key={token.id}>
              <input type="hidden" id={token.name} value={token.value} onChange={this.handleChange}/>
            </div>

            <div className={'field'} key={title.id}>
              <label className={'label'}>{title.name}</label>
              <div className={'control'}>
                <input className={'input'} type="text" id={title.name} value={title.value} onChange={this.handleChange}/>
              </div>
            </div>

            <div className={'field'} key={slug.id}>
              <label className={'label'}>{slug.name}</label>
              <div className={'control'}>
                <input className={'input'} type="text" id={slug.name} value={slug.value} onChange={this.handleChange}/>
              </div>
            </div>

            <div className={'field'} key={parent.id}>
              <label className={'label'}>{parent.name}</label>
              <div className={'control'}>
                <div className={'select'}>
                  <select id={parent.name} value={parent.choice_values[0]} onChange={this.handleChange}>
                    {parent.choice_values.map(option => {
                      return <option key={option.key} value={option.value}>{option.label}</option>
                    })}
                  </select>
                </div>
              </div>
            </div>


            <div key={pageId}>
              <button key={pageId} value={pageId} onClick={this.addContent}>Ajouter du contenu</button>
              {pageContents.value.map(data => {
                return (<ContentForm key={data.id}>{data}</ContentForm>)
              })}
            </div>
            <input type="submit" value="Submit"/>
          </form>
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
