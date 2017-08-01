//Require Mongoose
var mongoose = require('mongoose');
var mongoosePaginate = require('mongoose-paginate');
//Define a schema
var Schema = mongoose.Schema;

var EpisodeModelSchema = new Schema({
    title : String,
    date : String,
    imageurl: String,
    audiourl: String,
    hash: String
});
EpisodeModelSchema.plugin(mongoosePaginate);
//Export function to create "SomeModel" model class
module.exports = mongoose.model('Episode', EpisodeModelSchema );
