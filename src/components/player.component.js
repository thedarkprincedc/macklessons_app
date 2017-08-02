
   import React, { Component } from 'react';
class Player extends Component {
     constructor(props){
        super(props);
        this.state = {
             selectedData: {}
        };
    }
   
    render(){
        return (  
            <div>
                <h2>{this.props.selectedData.title}</h2>
                <video controls poster={this.props.selectedData.imageurl} className="videoWindow">
                    <source src={this.props.selectedData.audiourl} type="audio/mp3"/>
                </video>
            </div>
        );
    }
}
export default Player;