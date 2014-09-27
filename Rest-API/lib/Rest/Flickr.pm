package Rest::Flickr;

use base qw/Rest::Social/;

sub new {
	my ($class, $params) = @_;
	my $self = $class->SUPER::new($params);
	return $self;
}


sub _parseResult {
	my ($self, $result) = @_;

	return {author => $result->{author}, lat => $result->{lat}, lng => $result->{lng}, description => $result->{description}, url => $result->{url}, thumbnail => $result->{thumbnail}, published_on => $result->{published_on} };
}