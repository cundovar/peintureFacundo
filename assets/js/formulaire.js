import React from 'react';
import axios from 'axios';
import { render } from "react-dom";
class Formulaire extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      name: '',
      email: '',
      message: ''
    }
  }
  handleSubmit(e){
    e.preventDefault();
    axios({
      method: "POST",
      url:"http://localhost:8030",
      data:  this.state
    }).then((response)=>{
      if (response.data.status === 'success') {
        alert("Message Sent.");
        this.resetForm()
      } else if (response.data.status === 'fail') {
        alert("Message failed to send.")
      }
    })
  }
  resetForm(){
    this.setState({name: " ", email: " ", message: " "})
  }
  render() {
    return(
      <div className="App">
        <form id="contact-form" onSubmit={this.handleSubmit.bind(this)} method="POST">
          <div className="form-group">
              <label htmlFor="name">prenom</label>
              <input type="text" className="form-control" id="name" value={this.state.name} onChange={this.onNameChange.bind(this)} />
          </div>
          <div className="form-group">
              <label htmlFor="exampleInputEmail1">adresse mail</label>
              <input type="email" className="form-control" id="email" aria-describedby="emailHelp" value={this.state.email} onChange={this.onEmailChange.bind(this)} />
          </div>
          <div className="form-group">
              <label htmlFor="message">Message</label>
              <textarea className="form-control" rows="5" id="message" value={this.state.message} onChange={this.onMessageChange.bind(this)} />
          </div>
          <button type="submit" className="btn btn-primary" id="submitForm">Envoyer</button>
        </form>
      </div>
    );
  }
  onNameChange(event) {
	  this.setState({name: event.target.value})
  }
  onEmailChange(event) {
	  this.setState({email: event.target.value})
  }
  onMessageChange(event) {
	  this.setState({message: event.target.value})
  }
}
export default Formulaire;


class FormulaireContact extends HTMLElement{
    connectedCallback(){
       render(<Formulaire/>,this)
    }}
    customElements.define('component-contact',FormulaireContact )
