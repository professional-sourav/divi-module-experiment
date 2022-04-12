// External Dependencies
import React, { Component } from 'react';

// Internal Dependencies
import './style.css';


class HelloWorld extends Component {

  static slug = 'csh_hello_world';

  render() {
    const Content = this.props.content;
    console.log(this.props);

    return (
      <>
      <h1>
        Here is the Output
      </h1>
      <div dangerouslySetInnerHTML={{ __html: this.props.__page }}></div>
      </>
    );
  }
}

export default HelloWorld;
