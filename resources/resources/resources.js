module.exports = function(app, mongoose, bodyParser){
    var request = require("request");
     var EpisodeModel = require("../models/episode.model.js");
     var defaultLimit = 25;
   //  var censusCSDSCIServerHost = "https://data.census.gov/cedsci/search/";
    app.get('/api/getEpisodeList', function(req, res) {
        var page = parseInt(req.query.page || 0),
            limit = parseInt(req.query.limit || defaultLimit);
        EpisodeModel.paginate({}, { 
            offset: page, 
            limit: limit,
            sort:{
        date: -1 //Sort by Date Added DESC
    } ,
        }, function(err, result) {
            if (err){
                res.send(err);
                return;
            }
            res.header("Access-Control-Allow-Origin", "*");
            res.json(result);
        });
    });
}