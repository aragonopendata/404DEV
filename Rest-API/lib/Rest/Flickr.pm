package Rest::Flickr;

sub new {
	my ( $class, $params ) = @_;
    my $objectRef = {
        END_POINT => 'http://opendata.aragon.es/socialdata/data?locality=Jaca&distance=1&source=flickr'
    };
    bless $objectRef, $class;
    return $objectRef;
}

sub init {
	my $self = shift;
	$self->{MECHANIZE} = WWW::Mechanize->new(autocheck => 0);

	return $self;
}

sub searchByName {
	my ($self, $lng, $lat , $distance ,$name );
}

sub searchByCords {
	
}

sub _parseResult {

}

sub _prepareQUery {

}