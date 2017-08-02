import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';
import { List, ListItem, ListSubHeader, ListDivider } from 'react-toolbox/lib/list';

class EpisodeList extends Component {
    constructor(props){
        super(props);
        this.state = {
             docs: []
        };
    }
    componentDidMount() {
        this.getEpisodeList().then(function(response){
            this.setState(response.data);
        }.bind(this));
    }
    getEpisodeList(){
        return axios.get('http://localhost:8080/api/getEpisodeList').then(function (response) {
            console.log(response);
            return response;
        }).catch(function (error) { console.log(error); });
    }
    handleListItem(){
        this[0].props.onEpisodeClicked(this);

        console.log(this);
    }
    render(){
        var state = this.state;
        const ListTest = () => (
            <List selectable ripple className="videoList">
                <ListSubHeader caption={this.props.header} />
                {
                    this.state.docs.map((obj) =>   
                        <ListItem
                            key={obj._id}
                            avatar={obj.imageurl}
                            caption={obj.title}
                            legend={obj.date||"444"} 
                            onClick={this.handleListItem.bind([this, obj])}
                            />         
                    )     
                }
            </List>
        );
        return (
           ListTest()
        );
    }
}
export default EpisodeList;