// let url = `${window.location.protocol}//${window.location.hostname}:${window.location.port}`;
 let url = `${window.location.protocol}//${window.location.hostname}`;

function get(fn, method, data) {
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: `${url}/api_twitter/public/api/v1/${fn}/${data}`,
            method: method,
            data: {},
            dataType: 'text'
        }).done(function(result) {
            if (Object.keys(result).length > 0) {
                resolve(result);
            } else {
                resolve([]);
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error('An error occurred, errorThrown:', errorThrown);
            console.error('An error occurred, status:', textStatus);
            console.error('An error occurred, jqXHR:', jqXHR);
            reject([]);
        });
    })
    .catch(function(error) {
        console.error('Error or empty result:', error);
        return []; 
    });
}