package Rest::API;
use Dancer ':syntax';

use Encode;
use JSON ();

use Rest::Places; 
use Data::Dumper;


our $VERSION = '0.1';

any ['post','get'] => '/search/' => sub {
	my $coordinates = {	lat =>params->{lat}, lng => params->{lng} };
    my $distance = params->{distance};
    my $name = params->{name} || '';

    my $placesApi = Rest::Places->new();
    $placesApi->init();
    my $results = $placesApi->search($coordinates,$distance,$name);

    return to_json($results);
};

true;