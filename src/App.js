import React, { Component } from 'react';
import logo from './logo.svg';
import './App.css';
import { Grid, Row, Col } from 'react-flexbox-grid';
import EpisodeList from './components/episode-list.component';
import Player from './components/player.component';

import PlayerService from './player.service';
class App extends Component {
   constructor(props){
        super(props);
        this.state = {
             selectedData: {}
        };
        
    }
  onEpisodeClicked(data){
  this.setState({selectedData : data[1]});
  this.plyr.player.source({
    poster:     data[1].imageurl,
      type:       'video',
      autoplay: true,
      sources: [{
        src:      data[1].audiourl,
        type:     'audio/mp3'
      }]
    });
  }
   componentDidMount() { debugger;
         this.plyr = PlayerService();
       
    }
  render() {
    return (
      <div className="App">
         <Grid fluid>
        <Row>
          <Col xs={6}>
           <Player selectedData={this.state.selectedData}/>
          </Col>
          <Col xs={6}>
          <div>
            <EpisodeList 
            header='List of Macklessons Episodes'
            onEpisodeClicked={this.onEpisodeClicked.bind(this)}
          ></EpisodeList>
          </div>
          </Col>
        </Row>
      </Grid>
      </div>
    );
  }
}

export default App;
