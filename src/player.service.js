export const PlayerService = () => {
    var service = {}
    service.autoplay = false;
    var instances = window.plyr.setup({
        debug: true
    });
    service.player = instances[0];
    return service;
}
export default PlayerService;