package Rest::Social;

use strict;
use warnings;

use WWW::Mechanize;
use Data::Dumper;
use JSON qw/from_json/;

sub new {
	my ( $class, $params ) = @_;
    my $objectRef = {
        END_POINT => 'http://opendata.aragon.es/socialdata/data?locality=name&distance=distancia&source='.$params->{type},
        END_POINT_CORDS => 'http://opendata.aragon.es/socialdata/data?center=lat,lng&distance=distancia&source='.$params->{type}
    };
    bless $objectRef, $class;
    return $objectRef;
}

sub init {
	my $self = shift;
	$self->{MECHANIZE} = WWW::Mechanize->new(autocheck => 0);
	return $self;
}

sub search {
	my ($self, $coordinates , $distance ,$name ) = @_;
	$self->_prepareQUery($coordinates, $distance, $name);
	print $self->{QUERY};
	my $response = $self->{MECHANIZE}->get($self->{QUERY});

	my $jsonString = $response->content;
	my $result = ();
	my $json = {}; 
	eval { $json = from_json($jsonString)};
	if($@){
		print "$@";
	}

	print Dumper($json);
}

sub _prepareQUery {
	my ($self, $coordinates, $distance , $name) = @_;
	if (!$name){
		$self->{END_POINT_CORDS} =~ s/lat/$coordinates->{lat}/;
		$self->{END_POINT_CORDS} =~ s/lng/$coordinates->{lng}/;
		$self->{END_POINT_CORDS} =~ s/distancia/$distance/;
		$self->{QUERY} = $self->{END_POINT_CORDS};
	}else{
		$self->{END_POINT} =~ s/name/$name/;
		$self->{END_POINT} =~ s/distancia/$distance/;
		$self->{QUERY} = $self->{END_POINT};
	}
}

1;